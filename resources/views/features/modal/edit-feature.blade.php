<!-- Feature Edit Modal -->
<div class="modal fade" id="featureEditModal" tabindex="-1" role="dialog" aria-labelledby="featureEditModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 70%;" role="document">
        <div class="modal-content w-100">
            <div class="modal-header">
                <h5 class="modal-title" id="featureEditModalTitle">แก้ไข <span id="coin-name-edit-title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" method="POST" action="{{ route('feature-edit') }}">
                @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="coin" class="col-4 col-form-label">Coin<span class="text-danger">*</span></label>
                                <div class="col-7">
                                    <input type="text" name="coin" required class="form-control" autocomplete="off"
                                            id="feature-e-coin" placeholder="ชื่อเหรียญ (ภาษาอังกฤษเท่านั้น)" style="text-transform: uppercase"
                                            oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hori-pass1" class="col-4 col-form-label">Type<span class="text-danger">*</span></label>
                                <div class="col-7">
                                    <select class="form-control" name="type" id="feature-e-type" required>
                                        <option value="" disabled selected>-- เลือกประเภท --</option>
                                        <option value="SHORT">SHORT</option>
                                        <option value="LONG">LONG</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stop_loss" class="col-4 col-form-label">Stop Loss<span class="text-danger">*</span></label>
                                <div class="col-7">
                                    <input type="number" name="stop_loss" required class="form-control" autocomplete="off"
                                            id="feature-e-stop_loss" step="0.00001" placeholder=""
                                            onblur="setNumberDecimal('stop_loss')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="usdt_pnl" class="col-4 col-form-label">USDT PNL</label>
                                <div class="col-7">
                                    <input type="number" name="usdt_pnl" class="form-control" autocomplete="off"
                                            id="feature-e-usdt_pnl" step="0.00001" placeholder=""
                                            onblur="setNumberDecimal('usdt_pnl')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="docdate" class="col-4 col-form-label">DocDate<span class="text-danger">*</span></label>
                                <div class="col-7">
                                    <input type="datetime-local" name="docdate" required class="form-control" autocomplete="off"
                                            id="feature-e-docdate" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hori-pass1" class="col-4 col-form-label">Status<span class="text-danger">*</span></label>
                                <div class="col-7">
                                    <select class="form-control" name="status" id="feature-e-status" required>
                                        <option value="" disabled selected>-- เลือกสถานะ --</option>
                                        <option value="WATCH">WATCH</option>
                                        <option value="OPEN">OPEN</option>
                                        <option value="CLOSE">CLOSE</option>
                                        <option value="STOPLOSS">STOPLOSS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-4 col-form-label">Description</label>
                                <div class="col-7">
                                    <textarea name="description" class="form-control" id="feature-e-description" rows="5" style="height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pl-4" style="border-left: 1px solid #ddd;">
                            <div class="row py-2">
                                <div class="col-md-2">
                                    <h5>Entry</h5>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group row">
                                        <label for="entry1" class="col-3 col-form-label">Entry 1<span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input type="number" name="entry1" required class="form-control" autocomplete="off"
                                                    id="feature-e-entry1" step="0.00001" placeholder=""
                                                    onblur="setNumberDecimal('entry1')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="entry2" class="col-3 col-form-label">Entry 2</label>
                                        <div class="col-8">
                                            <input type="number" name="entry2" class="form-control" autocomplete="off"
                                                    id="feature-e-entry2" step="0.00001" placeholder=""
                                                    onblur="setNumberDecimal('entry2')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="entry3" class="col-3 col-form-label">Entry 3</label>
                                        <div class="col-8">
                                            <input type="number" name="entry3" class="form-control" autocomplete="off"
                                                    id="feature-e-entry3" step="0.00001" placeholder=""
                                                    onblur="setNumberDecimal('entry3')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="row py-2">
                                <div class="col-md-2">
                                    <h5>Target</h5>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group row">
                                        <label for="target1" class="col-3 col-form-label">Target 1<span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input type="number" name="target1" required class="form-control" autocomplete="off"
                                                    id="feature-e-target1" step="0.00001" placeholder=""
                                                    onblur="setNumberDecimal('target1')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="target2" class="col-3 col-form-label">Target 2</label>
                                        <div class="col-8">
                                            <input type="number" name="target2" class="form-control" autocomplete="off"
                                                    id="feature-e-target2" step="0.00001" placeholder=""
                                                    onblur="setNumberDecimal('target2')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="target3" class="col-3 col-form-label">Target 3</label>
                                        <div class="col-8">
                                            <input type="number" name="target3" class="form-control" autocomplete="off"
                                                    id="feature-e-target3" step="0.00001" placeholder=""
                                                    onblur="setNumberDecimal('target3')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-4 text-right">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Edit
                            </button>
                            <button type="reset"
                                    class="btn btn-secondary waves-effect m-l-5"
                                    class="close" data-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="e_id" id="feature-e-id" value="">
                </form>
            </div>
        </div>
    </div>
</div>