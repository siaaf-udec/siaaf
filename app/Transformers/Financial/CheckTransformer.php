<?php

namespace App\Transformers\Financial;


use App\Container\Financial\src\Check;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class CheckTransformer extends TransformerAbstract
{
    public function transform( Check $check )
    {
        return [
            'id'            =>  isset( $check->{ primaryKey() } ) ? $check->{ primaryKey() } : 0,
            'check'         =>  isset( $check->{ check() } ) ? $check->{ check() } : 0,
            'pay_to'        =>  isset( $check->{ pay_to() } ) ? $check->{ pay_to() } : __('financial.generic.empty'),
            'status'        =>  isset( $check->{ status() } ) ? $check->{ status() } : 0,
            'status_name'   =>  isset( $check->{ 'status_name' } ) ? $check->{ 'status_name' } : __('financial.generic.empty'),
            'status_class'  =>  isset( $check->{ 'class_name' } ) ? $check->{ 'class_name' } : null,
            'status_label'  =>  isset( $check->{ 'status_label' } ) ? $check->{ 'status_label' } : null,
            'created_at'    =>  isset( $check->{ created_at() } ) ? $check->{ created_at() }->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset( $check->{ updated_at() } ) ? $check->{ updated_at() }->format('Y-m-d H:i:s') : null,
            'delivered_at'  =>  isset( $check->{ delivered_at() } ) ? $check->{ delivered_at() }->format('Y-m-d') : null,
            'deleted_at'    =>  isset( $check->{ deleted_at() } ) ? $check->{ deleted_at() }->format('Y-m-d H:i:s') : null,
            'is_dirty'      =>  $this->addChanges( $check ),
            'actions'       =>  $this->getActions( $check )
        ];
    }

    /**
     * @param Check $check
     * @return bool|string
     */
    public function getActions( Check $check )
    {
        try {
            $log = actionLink(
                'javascript:;',
                'log',
                'fa fa-eye',
                ['data-id' => $check->{ primaryKey() } ],
                'Ver Log'
            );
            if (isset( $check->{ deleted_at() })) {
                return $log;
            } else {
                $edit  = actionLink(
                    'javascript:;',
                    'edit',
                    'fa fa-pencil',
                    ['data-id' => $check->{ primaryKey() }, 'data-original-title' => trans('javascript.tooltip.edit') ],
                    __('financial.buttons.edit')
                );

                $trash = actionLink(
                    'javascript:;',
                    'trash',
                    'fa fa-trash',
                    ['data-id' => $check->{ primaryKey() }, 'data-original-title' => trans('javascript.tooltip.delete') ],
                    __('financial.buttons.delete')
                );
                return createDropdown( [$edit, $trash, $log] );
            }

        } catch ( \Throwable $e ) {
            report( $e );
            return false;
        }
    }

    /**
     * @param Check $check
     * @return array
     */
    public function addChanges(Check $check  )
    {
        $audits = $check->audits()->with('user:id,name,lastname,phone,email')->latest()->get();
        $manager = new Manager;
        $audits = new Collection( $audits, new AuditsTransform );
        return $manager->createData( $audits )->toArray();
    }
}