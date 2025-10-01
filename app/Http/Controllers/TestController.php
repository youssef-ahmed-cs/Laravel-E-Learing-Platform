<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Http\Resources\TestResource;
use App\Models\Test;

class TestController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Test::class);

        return TestResource::collection(Test::all());
    }

    public function store(TestRequest $request)
    {
        $this->authorize('create', Test::class);

        return new TestResource(Test::create($request->validated()));
    }

    public function show(Test $test)
    {
        $this->authorize('view', $test);

        return new TestResource($test);
    }

    public function update(TestRequest $request, Test $test)
    {
        $this->authorize('update', $test);

        $test->update($request->validated());

        return new TestResource($test);
    }

    public function destroy(Test $test)
    {
        $this->authorize('delete', $test);

        $test->delete();

        return response()->json();
    }
}
