<?php

namespace App\Container\Financial\src\Repository;

use App\Container\Financial\src\AdditionSubtraction;
use App\Container\Financial\src\Extension;
use App\Container\Financial\src\Interfaces\FinancialAddSubInterface;
use App\Container\Financial\src\Interfaces\Methods;
use App\Container\Financial\src\SubjectProgram;
use App\Transformers\Financial\AdditionSubtractionDataTableTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;

class AddSubRepository extends Methods implements FinancialAddSubInterface
{
    /**
     * @var StatusRequestRepository
     */
    private $statusRequestRepository;
    /**
     * @var CostServiceRepository
     */
    private $costServiceRepository;

    /**
     * ExtensionRepository constructor.
     * @param StatusRequestRepository $statusRequestRepository
     * @param CostServiceRepository $costServiceRepository
     */
    public function __construct(StatusRequestRepository $statusRequestRepository, CostServiceRepository $costServiceRepository)
    {
        parent::__construct( AdditionSubtraction::class );
        $this->statusRequestRepository = $statusRequestRepository;
        $this->costServiceRepository = $costServiceRepository;
    }

    /**
     * @param $model
     * @param $request
     * @return mixed
     */
    public function process($model, $request )
    {
        $status = $this->statusRequestRepository->getId( 'ADD_REMOVE_SUBJECTS', 'ENVIADO' );
        $cost_service = $this->costServiceRepository->getId( 'ADD_REMOVE_SUBJECTS' );
        $model->{ action_subject() }    =   $request->action;
        //$model->{ approval_date() }     =   $request->approval_date;
        $model->{ subject_fk() }        =   $request->subject_matter;
        $model->{ student_fk() }        =   auth()->user()->id;
        $model->{ status_fk() }         =   $status->{ primaryKey() };
        $model->{ cost_service_fk() }   =   $cost_service->{ primaryKey() };
        //$model->{ approved_by() }       =   auth()->user()->id;
        return $model->save();
    }

    public function updateAdminAddSub( $request, $id )
    {
        $approved = $this->statusRequestRepository->getId( 'ADD_REMOVE_SUBJECTS', 'APROBADO' );
        $model = $this->getModel()->find( $id );
        if ( $request->status == $approved->{ primaryKey() } ) {
            if ( !isset( $model->{ approval_date() } ) ) {
                $model->{ approval_date() } = now();
                $model->{ approved_by() }   = auth()->user()->id;
            }
        }
        $model->{ status_fk() }         =   $request->status;
        return $model->save();
    }

    /**
     * @param array $status
     * @return mixed
     */
    public function count( $status = [] )
    {
        $model = $this->getModel();
        $model = ( $status ) ? $model->whereIn( status_fk(),  $status ) : $model;
        return $model->count();
    }

    /**
     * @return mixed
     */
    public function availableStatus()
    {
        return $this->statusRequestRepository->getNames( 'ADD_REMOVE_SUBJECTS' );
    }

    /**
     * @param $request
     * @return mixed
     */
    public function storeStudentAddSub( $request )
    {
        $status = $this->statusRequestRepository->getId( 'ADD_REMOVE_SUBJECTS', 'ENVIADO' );
        $cost_service = $this->costServiceRepository->getId( 'ADD_REMOVE_SUBJECTS' );
        $model = $this->getModel();
        $model->{ action_subject() }        =  $request->action;
        $model->{ subject_fk() }            =  $request->subject_matter;
        $model->{ student_fk() }            =  auth()->user()->id;
        $model->{ cost_service_fk() }       =  $cost_service->{ primaryKey() };
        $model->{ status_fk() }             =  $status->{ primaryKey() };
        return $model->save();
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function updateStudentAddSub( $request, $id )
    {
        $model = auth()->user()->additionSubtraction()->find( $id );
        $model->{ action_subject() }    =   $request->action;
        $model->{ subject_fk() }        =   $request->subject_matter;
        return $model->save();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteStudentAddSub( $id )
    {
        $model = $this->getAuth(['status'], $id);
        return ( $model && $model->status->{ status_name() } == 'PENDIENTE' || $model->status->{ status_name() } == 'ENVIADO' ) ? $model->forceDelete() : false;
    }

    /**
     * @param $id
     * @param bool $whitRelations
     * @return mixed
     */
    public function subjectRelation($id, $whitRelations = false )
    {
        $model = $this->getAuth( [], $id );
        $model = SubjectProgram::where( subject_fk() , $model->{ subject_fk() } );
        $model = $whitRelations ? $model->with(['programs', 'subjects', 'teachers:id,name,lastname,phone,email']) : $model;
        return $model->first();
    }

    /**
     * @param int $quantity
     * @param null $status
     * @return Collection
     */
    public function getAllPaginate($quantity = 5, $status = null )
    {
        $items = $this->getModel()->with([
            'subject' => function ($q) {
                return $q->with([
                    'programs',
                    'teachers:id,name,lastname,phone,email'
                ]);
            },
            'status',
            'secretary:id,name,lastname,phone,email',
            'student:id,name,lastname,phone,email',
        ])->withCount('comments')->latest();

        if ( $status ) {
            $items = $items->whereHas('status', function ($query) use ($status) {
                $query->where( primaryKey(), $status );
            });
        }

        $items = $items->paginate( $quantity );


        $collection = $items->getCollection()
            ->map(function( $model ) {
                return $this->formatData( $model );
            })->toArray();

        return customPagination( $collection,  $items);
    }

    /**
     * Retrieve the auth user extension
     *
     * @param array $relations
     * @param $id
     * @return mixed
     */
    public function getAuth(array $relations = [], $id)
    {
        $model = auth()->user()->additionSubtraction();
        return ( count( $relations ) ) ? $model->with( $relations )->findOrFail( $id ) : $model->findOrFail( $id ) ;
    }

    /**
     * @param $model
     * @return array
     */
    public function formatData( $model )
    {
        return (new AdditionSubtractionDataTableTransformer)->transform( $model );
    }
}