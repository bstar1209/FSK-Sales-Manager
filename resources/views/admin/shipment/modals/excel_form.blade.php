<form id="excel_form" action="{{ route('admin.shipment.export_excel') }}" method="POST">
    @csrf
    <input type="hidden" name="importIds[]" id="excel_import_ids" />
</form>
<input id="import-excel-browser" type="file" name="file" class="d-none" />
