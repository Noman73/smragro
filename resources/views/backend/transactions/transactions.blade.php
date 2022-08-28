



  
  <!-- Modal -->
  <div class="modal fade" id="voucerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Voucer</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="">
                <div class="form-group">
                    <label for="">Ledger</label>
                    <select name="" id="voucer_ledger" class="form-control">
                    </select>
                    <div class="invalid-feedback" id="voucer_ledger_msg">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Sub Ledger</label>
                    <select name="" id="voucer_sub_ledger" class="form-control"></select>
                    <div class="invalid-feedback" id="voucer_sub_ledger_msg"></div>
                </div>
                <div class="form-group">
                    <label for="">Voucer Type</label>
                    <select name="" id="voucer_type" class="form-control">
                        <option value="">--select-</option>
                        <option value="1">Deposit</option>
                        <option value="1">Expence</option>
                    </select>
                    <div class="invalid-feedback" id="voucer_type_msg">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Ammount</label>
                    <input type="text" class="form-control" id="voucer_ammount" placeholder="0.00">
                    <div class="invalid-feedback" id="voucer_ammount_msg">
                    </div>
                </div>
                <div class="form-group">
                      <input type="radio" onchange="voucerPaymentMethod()"  name="voucer_payment_method_type[]" id="cash" value="0" checked>
                      <label for="cash">Cash</label>
                      <input type="radio" onchange="voucerPaymentMethod()" name="voucer_payment_method_type[]" id="regular" value="1">
                      <label for="walking">Bank</label>
                      <div class="invalid-feedback" id='voucer_payment_method_msg'></div>
                </div>
                <div class="form-group bank">
                    <label for="">Bank</label>
                    <select name="" id="voucer_bank" class="form-control">
                    </select>
                    <div class="invalid-feedback" id="voucer_bank_msg"></div>
                </div>
                <div class="form-group bank">
                    <label for="">Cheque No</label>
                    <input type='number' name="" id="voucer_cheque_no" class="form-control">
                    <div class="invalid-feedback" id="voucer_cheque_no_msg"></div>
                </div>
                <div class="form-group bank">
                    <label for="">Cheque Photo</label>
                    <input type='file' name="" id="voucer_cheque_photo" class="form-control">
                    <div class="invalid-feedback" id="voucer_cheque_photo_msg"></div>
                </div>
                <div class="form-group">
                    <label for="">Comment</label>
                    <textarea name="" id="voucer_comment" rows="2" class="form-control"></textarea>
                    <div class="invalid-feedback" id="voucer_comment_msg"></div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="voucerFormRequest()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>