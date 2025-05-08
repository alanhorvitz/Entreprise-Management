<div class="p-6">
    <div class="flex items-center gap-6 mb-8">
        <div class="avatar">
            <div class="w-24 h-24 mask mask-squircle">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->user->first_name . ' ' . $employee->user->last_name) }}&background=random" 
                     alt="{{ $employee->user->first_name }} {{ $employee->user->last_name }}" />
            </div>
        </div>
        <div>
            <h2 class="text-2xl font-bold">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</h2>
            <p class="text-base-content/70">{{ $employee->employee_code }}</p>
            <div class="badge {{ $employee->status->status === 'active' ? 'badge-success' : 'badge-warning' }} mt-2">
                {{ $employee->status->status }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="card bg-base-200">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">
                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:user-id-bold-duotone"></span>
                    Personal Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium">CIN</label>
                        <p>{{ $employee->cin }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Address</label>
                        <p>{{ $employee->address }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Personal Number</label>
                        <p>{{ $employee->personal_num }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="card bg-base-200">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">
                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:case-bold-duotone"></span>
                    Professional Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium">Professional Email</label>
                        <p>{{ $employee->professional_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Professional Number</label>
                        <p>{{ $employee->professional_num ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Departments</label>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($employee->departments as $department)
                                <div class="badge badge-primary">{{ $department->name }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="card bg-base-200">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">
                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:document-bold-duotone"></span>
                    Employment Details
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium">Types</label>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($employee->types as $type)
                                <div class="badge badge-ghost tooltip" data-tip="From: {{ $type->pivot->in_date ?? 'Not set' }} - To: {{ $type->pivot->out_date ?? 'Current' }}">
                                    {{ $type->type }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Operator</label>
                        <p>{{ $employee->operator?->operator ?? 'Not assigned' }}</p>
                    </div>
                    <div class="flex justify-between ">
                        <label class="text-sm font-medium">Status</label>
                        <div class="badge {{ $employee->status->status === 'active' ? 'badge-success' : 'badge-warning' }}">
                            {{ $employee->status->status }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card bg-base-200">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">
                    <span class="iconify w-5 h-5 mr-2" data-icon="solar:info-circle-bold-duotone"></span>
                    Additional Information
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium">Project Employee</label>
                        <div class="badge {{ $employee->is_project ? 'badge-info' : 'badge-ghost' }}">
                            {{ $employee->is_project ? 'Yes' : 'No' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">ANAPEC Employee</label>
                        <div class="badge {{ $employee->is_anapec ? 'badge-accent' : 'badge-ghost' }}">
                            {{ $employee->is_anapec ? 'Yes' : 'No' }}
                        </div>
                    </div>
                    @if($employee->ice)
                        <div>
                            <label class="text-sm font-medium">ICE</label>
                            <p>{{ $employee->ice }}</p>
                        </div>
                    @endif
                    @if($employee->cnss)
                        <div>
                            <label class="text-sm font-medium">CNSS</label>
                            <p>{{ $employee->cnss }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 