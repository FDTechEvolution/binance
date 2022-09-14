<!-- Feature Add Modal -->
<div class="modal fade" id="featureConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="featureConfirmDelete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 30%;" role="document">
        <div class="modal-content w-100">
            <div class="modal-body">
                <form role="form" method="POST" action="{{ route('feature-delete') }}">
                @csrf
                    <div class="row">
                        <div class="col-12 text-center">
                            <h4 class="py-3">ยืนยันการลบ <span id="delete-coin-name"></span></h4>
                            <p>Type : <span id="delete-coin-type"></span> | Status : <span id="delete-coin-status"></span></p>
                        </div>
                    </div>

                    <div class="form-group row mt-4 text-center">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-3">
                                Confirm
                            </button>
                            <button type="reset"
                                    class="btn btn-secondary waves-effect ml-3"
                                    class="close" data-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="feature-id-delete" value="">
                </form>
            </div>
        </div>
    </div>
</div>