<?php

namespace Promatik\CreateDummyOperation\Http\Controllers\Operations;

use Illuminate\Support\Facades\Route;

trait CreateDummyOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupCreateDummyRoutes($segment, $routeName, $controller)
    {
        Route::post($segment . '/create-dummy', [
            'as' => $routeName . '.createDummy',
            'uses' => $controller . '@createDummy',
            'operation' => 'createDummy',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateDummyDefaults()
    {
        $this->crud->allowAccess('createDummy');

        $this->crud->operation('createDummy', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            if (method_exists($this->crud->getModel(), 'factory')) {
                $this->crud->addButton('top', 'create_dummy', 'view', 'createdummyoperation::buttons.create_dummy');
            }
        });
    }

    /**
     * Show the form for creating inserting a new row.
     *
     * @return Response
     */
    public function createDummy()
    {
        // Access
        $this->crud->hasAccessOrFail('createDummy');

        // Check if Model has Factory trait
        if (!method_exists($this->crud->getModel(), 'factory')) {
            return response()->json([
                'title' => trans('createdummyoperation::createdummyoperation.create_dummy_error_title'),
                'message' => trans('createdummyoperation::createdummyoperation.create_dummy_error_message'),
            ], 500);
        }

        // Options
        $TRUNCATE = 1;
        $CREATE = 2;

        $count = request()->input('count');
        $option = request()->input('option');

        // Truncate table
        if ($option & $TRUNCATE) {
            $this->crud->getModel()->truncate();
            $messages[] = trans('createdummyoperation::createdummyoperation.create_dummy_success_truncate');
        }

        // Create dummy entries
        if ($option & $CREATE) {
            $this->crud->getModel()::factory()->count($count)->create();
            $messages[] = trans_choice('createdummyoperation::createdummyoperation.create_dummy_sucess_message', $count, ['count' => $count]);
        }

        return response()->json([
            'title' => trans('createdummyoperation::createdummyoperation.create_dummy_sucess_title'),
            'message' => join(' ', $messages),
        ]);
    }
}
