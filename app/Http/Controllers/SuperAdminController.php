<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersManagment\SuperAdminRequest;
use App\Http\Resources\SuperAdminResource;
use App\Models\SuperAdmin;

class SuperAdminController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', SuperAdmin::class);

        return SuperAdminResource::collection(SuperAdmin::all());
    }

    public function store(SuperAdminRequest $request)
    {
        $this->authorize('create', SuperAdmin::class);

        return new SuperAdminResource(SuperAdmin::create($request->validated()));
    }

    public function show(SuperAdmin $superAdmin)
    {
        $this->authorize('view', $superAdmin);

        return new SuperAdminResource($superAdmin);
    }

    public function update(SuperAdminRequest $request, SuperAdmin $superAdmin)
    {
        $this->authorize('update', $superAdmin);

        $superAdmin->update($request->validated());

        return new SuperAdminResource($superAdmin);
    }

    public function destroy(SuperAdmin $superAdmin)
    {
        $this->authorize('delete', $superAdmin);

        $superAdmin->delete();

        return response()->json();
    }
}
