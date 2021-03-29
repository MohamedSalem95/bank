@extends('layouts.app')

@section('content')
    <h2> Group Master </h2>
    <a href="{{ route('groups.create') }}">
        <b>
        <i class="fas fa-plus-circle"></i> Create new group
        </b>
    </a>
    <p>
        <b class="text-muted">
            Total of {{ $groups->count() }} {{ Str::plural('group', $groups->count()) }}
        </b>
    </p>
    <table class="table table-striped table-sm table-hover table-bordered">

        <thead>
            <tr>

                <th> Name </th>
                <th> Company </th>
                <th> Status </th>
                <th> Actions </th>
            </tr>
        </thead>

        <tbody>
        @forelse($groups as $group)
            <tr>
                <td> {{ $group->name }} </td>
                <td> <b class="text-success"> {{ $group->company->name }} </b> </td>
                <td class="@if($group->status == 'inactive') text-danger font-weight-bold @endif"> {{ $group->status }} </td>
                <td>
                    <a href="{{ route('groups.edit', ['group' => $group->id]) }}">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </td>

            </tr>
        @empty

        <p>
            <b> 
                No groups Here yet.. 
                you can may create a new one <a href="{{ route('groups.create') }}"> Here </a>    
            </b>
        </p>

        @endforelse
        </tbody>

    </table>

   

@stop