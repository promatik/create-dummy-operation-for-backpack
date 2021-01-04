@php
// Store configs
$env = \Config::get('backpack.crud.operations.createDummy.environment', ['local', 'testing']);
$default = \Config::get('backpack.crud.operations.createDummy.default', 25);
@endphp

@if ($crud->hasAccess('createDummy') && in_array(\App::environment(), $env) )
<div class="create-dummy">
    <a href="" class="btn btn-outline-primary btn-dummy">
        <span class="ladda-label">
            <i class="la la-plus"></i>
            <span>{{ trans('createdummyoperation::createdummyoperation.create_dummy_add') }} {{ $crud->entity_name_plural }}</span>
        </span>
    </a>

    <form action="{{ url($crud->route.'/create-dummy') }}">
        <p>{!! trans('createdummyoperation::createdummyoperation.create_dummy_alert') !!}</p>
        <input name="count" type="number" placeholder="{{ $default }}" class="form-control" />
        @csrf
    </form>
</div>
@endif

{{-- Button Javascript --}}
@push('after_scripts')
<script>
const crudRoute = '{{ Str::slug($crud->getRoute()) }}';
// Dummy setup
const dummyBtn = document.querySelector('.create-dummy a');
const dummyForm = document.querySelector('.create-dummy form');
const dummyFormInput = dummyForm.querySelector('input[name="count"]');
const dummyFormToken = dummyForm.querySelector('input[name="_token"]').value;
// Options
const TRUNCATE = 1;
const CREATE   = 2;
// API fetch
const fetchCreateDummy = option => {
    if(option) {
        let body = new FormData(dummyForm);
        body.append("option", option);
        // Fetch the action
        fetch(dummyForm.getAttribute("action"), {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "X-CSRF-Token": dummyFormToken
            },
            body,
        }).then(response => {
            response.json().then(function(data) {
                // Show an alert message
                new Noty({
                    type: response.ok ? 'success' : 'error',
                    text: `<strong>${data.title}</strong><br>${data.message}`,
                }).show();
                
                // Redraw table with new entries
                if(response.ok) {
                    crud.table.draw(false);
                }
            });
        });
    }
}
// Override 
const openCreateDummyModal = e => {
    if(e) e.preventDefault();
    // Alert
    swal({
        content: dummyForm,
        icon: 'info',
        buttons: {
            truncateAndCreate: {
                text: "{!! trans('createdummyoperation::createdummyoperation.truncate_create') !!}",
                value: TRUNCATE | CREATE,
                visible: true,
                className: "bg-danger",
            },
            create: {
                text: "{!! trans('createdummyoperation::createdummyoperation.create') !!}",
                value: CREATE,
                visible: true,
                className: "bg-primary",
            },
        },
    }).then(option => {
        if(option & TRUNCATE) {
            swal({
                text: "{!! trans('createdummyoperation::createdummyoperation.create_dummy_confirm_truncate') !!}".replace(':number', dummyFormInput.value),
                icon: 'warning',
                buttons: ["{!! trans('createdummyoperation::createdummyoperation.cancel') !!}", "{!! trans('createdummyoperation::createdummyoperation.truncate_create') !!}"],
                dangerMode: true,
            }).then(value => {
                // Proceed with the fetch or go back to previoud modal
                value ? fetchCreateDummy(option) : openCreateDummyModal();
            });
        } else if(option & CREATE) {
            fetchCreateDummy(option)
        }
    });
}
dummyBtn.onclick = openCreateDummyModal;
// Save current count value localy
dummyFormInput.oninput = () => localStorage.setItem(`${crudRoute}_create_dummy_count`, dummyFormInput.value);
// Load count value from local storage
dummyFormInput.value = localStorage.getItem(`${crudRoute}_create_dummy_count`, {{ $default }});
</script>
@endpush

{{-- Button Style --}}
@push('after_styles')
<style>
.create-dummy {
    display: inline-block;
}
.create-dummy form {
    display: none;
}
.swal-modal input {
    max-width: 65%;
    margin: auto;
}
.swal-modal .swal-text {
    text-align: center;
}
</style>
@endpush 