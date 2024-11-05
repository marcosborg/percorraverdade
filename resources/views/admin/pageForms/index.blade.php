@extends('layouts.admin')
@section('content')
<div class="content">
    @can('page_form_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.page-forms.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.pageForm.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.pageForm.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-PageForm">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.pageForm.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pageForm.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pageForm.fields.phone') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pageForm.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pageForm.fields.message') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.pageForm.fields.rgpd') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pageForms as $key => $pageForm)
                                    <tr data-entry-id="{{ $pageForm->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $pageForm->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pageForm->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pageForm->phone ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pageForm->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $pageForm->message ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $pageForm->rgpd ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $pageForm->rgpd ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            @can('page_form_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.page-forms.show', $pageForm->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('page_form_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.page-forms.edit', $pageForm->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('page_form_delete')
                                                <form action="{{ route('admin.page-forms.destroy', $pageForm->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('page_form_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.page-forms.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-PageForm:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection