@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
    <style>
        .service-badge {
            font-size: 0.8rem;
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
    <h3 class="page-title">Services de la Pharmacie</h3>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Services</li>
    </ul>
</div>
<div class="col-sm-5 col">
    <a href="#add_service" data-toggle="modal" class="btn btn-primary float-right mt-2">
        <i class="fas fa-plus"></i> Ajouter un Service
    </a>
</div>
@endpush

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="service-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Responsable</th>
                                <th>Statut</th>
                                <th>Date de création</th>
                                <th class="text-center action-btn">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_service" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('services.store')}}" id="addServiceForm">
                    @csrf
                    <div class="row form-row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Nom du Service <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Responsable</label>
                                <select class="form-control select2" name="responsible_id">
                                    <option value="">Sélectionner un responsable</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Statut</label>
                                <select class="form-control" name="status">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                    <option value="maintenance">En maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Heures d'ouverture</label>
                                <input type="text" name="opening_hours" class="form-control" placeholder="Ex: 8h-18h">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /ADD Modal -->

<!-- Edit Modal -->
<div class="modal fade" id="edit_service" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('services.update')}}" id="editServiceForm">
                    @csrf
                    @method("PUT")
                    <div class="row form-row">
                        <div class="col-12">
                            <input type="hidden" name="id" id="edit_id">
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Nom du Service <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Responsable</label>
                                <select class="form-control select2" name="responsible_id" id="edit_responsible_id">
                                    <option value="">Sélectionner un responsable</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" id="edit_description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Statut</label>
                                <select class="form-control" name="status" id="edit_status">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                    <option value="maintenance">En maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Heures d'ouverture</label>
                                <input type="text" name="opening_hours" class="form-control" id="edit_opening_hours">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Sauvegarder</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Modal -->

<!-- Service Details Modal -->
<div class="modal fade" id="service_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 id="detail_name"></h4>
                        <p id="detail_description" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Responsable:</strong> <span id="detail_responsible"></span></p>
                        <p><strong>Statut:</strong> <span id="detail_status" class="badge"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Heures d'ouverture:</strong> <span id="detail_opening_hours"></span></p>
                        <p><strong>Créé le:</strong> <span id="detail_created_at"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- /Service Details Modal -->
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        // Initialisation de la table DataTable
        var table = $('#service-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('services.index')}}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description', render: function(data) {
                    return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') : '';
                }},
                {data: 'responsible.name', name: 'responsible.name', defaultContent: 'Non attribué'},
                {data: 'status', name: 'status', render: function(data) {
                    var badgeClass = 'badge-success';
                    if(data === 'inactive') badgeClass = 'badge-danger';
                    if(data === 'maintenance') badgeClass = 'badge-warning';
                    return '<span class="badge ' + badgeClass + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                }},
                {data: 'created_at', name: 'created_at', render: function(data) {
                    return new Date(data).toLocaleDateString();
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false,
                 render: function(data, type, row) {
                    return `
                        <div class="actions text-center">
                            <a class="btn btn-sm bg-info-light viewbtn" href="#" data-id="${row.id}">
                                <i class="fe fe-eye"></i> Voir
                            </a>
                            <a class="btn btn-sm bg-success-light editbtn" href="#" data-id="${row.id}">
                                <i class="fe fe-pencil"></i> Editer
                            </a>
                            <a class="btn btn-sm bg-danger-light deletebtn" href="#" data-id="${row.id}">
                                <i class="fe fe-trash"></i> Supprimer
                            </a>
                        </div>
                    `;
                }},
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            }
        });

        // Gestion de l'affichage des détails
        $('#service-table').on('click', '.viewbtn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '/admin/services/' + id,
                type: 'GET',
                success: function(response) {
                    $('#detail_name').text(response.name);
                    $('#detail_description').text(response.description);
                    $('#detail_responsible').text(response.responsible ? response.responsible.name : 'Non attribué');
                    $('#detail_opening_hours').text(response.opening_hours || 'Non spécifié');
                    $('#detail_created_at').text(new Date(response.created_at).toLocaleDateString());

                    // Gestion du statut avec badge coloré
                    var statusBadge = $('#detail_status');
                    statusBadge.text(response.status.charAt(0).toUpperCase() + response.status.slice(1));
                    statusBadge.removeClass('badge-success badge-danger badge-warning');

                    if(response.status === 'active') {
                        statusBadge.addClass('badge-success');
                    } else if(response.status === 'inactive') {
                        statusBadge.addClass('badge-danger');
                    } else {
                        statusBadge.addClass('badge-warning');
                    }

                    $('#service_details').modal('show');
                }
            });
        });

        // Gestion de l'édition
        $('#service-table').on('click', '.editbtn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '/admin/services/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#edit_id').val(response.id);
                    $('#edit_name').val(response.name);
                    $('#edit_description').val(response.description);
                    $('#edit_responsible_id').val(response.responsible_id).trigger('change');
                    $('#edit_status').val(response.status);
                    $('#edit_opening_hours').val(response.opening_hours);
                    $('#edit_service').modal('show');
                }
            });
        });

        // Gestion de la suppression
        $('#service-table').on('click', '.deletebtn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/services/' + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            table.ajax.reload();
                            Swal.fire(
                                'Supprimé!',
                                'Le service a été supprimé.',
                                'success'
                            );
                        },
                        error: function() {
                            Swal.fire(
                                'Erreur!',
                                'Une erreur est survenue lors de la suppression.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Validation du formulaire d'ajout
        $('#addServiceForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                name: {
                    required: "Le nom du service est obligatoire",
                    minlength: "Le nom doit contenir au moins 3 caractères"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        // Validation du formulaire d'édition
        $('#editServiceForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                name: {
                    required: "Le nom du service est obligatoire",
                    minlength: "Le nom doit contenir au moins 3 caractères"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush