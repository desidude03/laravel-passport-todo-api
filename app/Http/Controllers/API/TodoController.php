<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Todo;
use Validator;
use App\Http\Resources\TodoResource;
use Illuminate\Http\JsonResponse;

class TodoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $Todos = Todo::all();

        return $this->sendResponse(TodoResource::collection($Todos), 'Todos retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $Todo = Todo::create($input);

        return $this->sendResponse(new TodoResource($Todo), 'Todo created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $Todo = Todo::find($id);

        if (is_null($Todo)) {
            return $this->sendError('Todo not found.');
        }

        return $this->sendResponse(new TodoResource($Todo), 'Todo retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $Todo): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $Todo->title = $input['title'];
        $Todo->description = $input['description'];
        $Todo->save();

        return $this->sendResponse(new TodoResource($Todo), 'Todo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $Todo): JsonResponse
    {
        $Todo->delete();

        return $this->sendResponse([], 'Todo deleted successfully.');
    }
}