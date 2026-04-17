@extends('layouts.admin')

@section('title', __('messages.contact_management'))
@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3> <small>{{ __('messages.contact_inbox') }}</small></h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5   form-group pull-right top_search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ __('messages.search_objects') }}...">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" type="button">{{ __('messages.search') }}</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{ __('messages.contact_inbox') }}<small>{{ __('messages.manage_contacts') }}</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- Filter Buttons -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.contacts.index', ['filter' => 'all']) }}" 
                                       class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-default' }}">
                                        <i class="fa fa-list"></i> {{ __('messages.all') }}
                                    </a>
                                    <a href="{{ route('admin.contacts.index', ['filter' => 'unreplied']) }}" 
                                       class="btn btn-sm {{ $filter === 'unreplied' ? 'btn-warning' : 'btn-default' }}">
                                        <i class="fa fa-clock-o"></i> {{ __('messages.unreplied') }}
                                    </a>
                                    <a href="{{ route('admin.contacts.index', ['filter' => 'replied']) }}" 
                                       class="btn btn-sm {{ $filter === 'replied' ? 'btn-success' : 'btn-default' }}">
                                        <i class="fa fa-check"></i> {{ __('messages.replied') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($contacts->isEmpty())
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> {{ __('messages.no_contact_message') }}
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped jambo_table bulk_action">
                                    <thead>
                                        <tr class="headings">
                                            <th class="column-title">{{ __('messages.full_name') }}</th>
                                            <th class="column-title">{{ __('messages.email') }}</th>
                                            <th class="column-title">{{ __('messages.phone') }}</th>
                                            <th class="column-title">{{ __('messages.message') }}</th>
                                            <th class="column-title">{{ __('messages.status') }}</th>
                                            <th class="column-title">{{ __('messages.sent_date') }}</th>
                                            <th class="column-title no-link last"><span class="nobr">{{ __('messages.action') }}</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($contacts as $contact)
                                            <tr class="even pointer">
                                                <td>{{ $contact->full_name }}</td>
                                                <td>{{ $contact->email }}</td>
                                                <td>{{ $contact->phone_number }}</td>
                                                <td>
                                                    <span class="text-truncate" style="max-width: 200px;">
                                                        {{ Str::limit($contact->message, 50) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($contact->is_Reply == 1 || $contact->is_Reply == 'true')
                                                        <span class="label label-success">{{ __('messages.replied') }}</span>
                                                    @else
                                                        <span class="label label-warning">{{ __('messages.unreplied') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="last">
                                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-info" title="{{ __('messages.view_details') }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-contact" data-id="{{ $contact->id }}" title="{{ __('messages.delete') }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.confirm_delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('messages.delete_contact_confirmation') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">{{ __('messages.delete') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteContactId = null;

    $(document).ready(function() {
        $('.delete-contact').click(function() {
            deleteContactId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        $('#confirmDelete').click(function() {
            if (deleteContactId) {
                $.ajax({
                    url: '/admin/contacts/' + deleteContactId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        alert('{{ __('messages.delete_error') }}');
                        $('#deleteModal').modal('hide');
                    }
                });
            }
        });
    });
</script>

@endsection
