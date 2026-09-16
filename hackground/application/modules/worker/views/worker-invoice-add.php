<style>

        .invoice-box {
            background: #fff;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .table input {
            width: 100%;
            border: none;
            background: transparent;
        }
        .table input:focus {
            outline: none;
            box-shadow: none;
        }
        .table input.invalid{
            border: 1px solid red;
        }
        .total-box {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
<?php 

//print_r($detail);
$addres=$detail['worker_address'];
//getFieldData('state_name', 'state_names', '', '', array('state_id' => $addres['worker_state'], 'state_lang' => admin_default_lang()))
$address_data=[
    $addres['worker_flat'],
    $addres['worker_street'],
    $addres['worker_address'],
    $addres['worker_pincode']
];
$address_data_loc=[
    $addres['worker_city'],
    
];
if($addres['worker_state']){
    $address_data_loc[]=getFieldData('state_name', 'state_names', '', '', array('state_id' => $addres['worker_state'], 'state_lang' => admin_default_lang()));
}
$address_data=array_filter($address_data);
$address_data_loc=array_filter($address_data_loc);
?>
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
<input type="hidden" name="ID" value="<?php echo $worker_id;?>"/>
<input type="hidden" name="page" value="<?php echo $page;?>"/>
<div class="container">
    <div class="invoice-box">

        <!-- ================= TOP SECTION ================= -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Issuer Details</h4>
                <p>
                    <strong>SnapHive</strong><br>
                    GSTIN : 19PKJPS5988D1Z0<br>
                    Eraqi Street , South Bazar , Andal, West Bengal , 713321<br>
                    +91 79033 71185<br>
                    moin.knockonce@gmail.com<br>
                    www.snaphive.com
                </p>
            </div>
            <div class="col-md-6 text-right">
                <h4>Recipient Details</h4>
                <p>
                    <strong><?php echo $detail['worker_name'];?></strong><br>
                    <?php echo implode(',',$address_data)?><br>
                    <?php echo implode(',',$address_data_loc)?><br>
                    <?php echo $detail['worker_phone']?><br>
                    <?php echo $detail['worker_email']?><br>
                </p>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4">
                <label><strong>Invoice Type</strong></label>
                <select class="form-control" name="invoice_type">
                    <option value="">-Select-</option>
                    <?php print_select_option($invoice_type, 'name_tkey','description_tkey'); ?>
                </select>
            </div>
        </div>


        <hr>
        <?php if($invoice_pre_item){?>
        <h5>Predefined Items</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <?php foreach($invoice_pre_item as $k=>$item){?>
                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        onchange="toggleItem(this, '<?php echo $item['name'];?>', <?php echo $item['qty'];?>, <?php echo $item['price'];?>)">
                    <label class="form-check-label">
                        <?php echo $item['name'];?> (₹<?php echo $item['price'];?>)
                    </label>
                </div>
                <?php }?>
               
                
            </div>
        </div>
        <?php }?>
        <!-- ================= INVOICE TABLE ================= -->
        <div class="table-responsive">
            <table class="table table-bordered" id="invoiceTable">
                <thead class="thead-light">
                <tr>
                    <th>Description</th>
                    <th width="100">Qty</th>
                    <th width="150">Rate</th>
                    <th width="150">Amount</th>
                    <th width="60">Action</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-primary btn-sm" onclick="addEmptyRow()">+ Add Row</button>

        <!-- ================= TOTAL SECTION ================= -->
        <div class="row mt-4">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right" id="subTotal">0.00</td>
                    </tr>
                    <tr class="total-box">
                        <td>Total</td>
                        <td class="text-right" id="grandTotal">0.00</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <p class="text-center text-muted">
            Thank you for your business!
        </p>

    </div>
    <button type="submit" class="btn btn-site mt-2">Save</button>
    <a class="btn btn-secondary  mt-2" href="<?php echo base_url('worker/view_edit/invoice/'.$worker_id)?>">Cancel</a>

</div>
</form>
<script>
function toggleItem(checkbox, name, qty, rate) {
    const tbody = document.querySelector("#invoiceTable tbody");

    if (checkbox.checked) {
        const row = document.createElement("tr");
        row.setAttribute("data-item", name);

        row.innerHTML = `
            <td>${name}<input type="hidden" placeholder="Custom item" value="${name}" name="itemname[]"></td>
            <td><input type="number" value="${qty}" min="1" oninput="calculate()" name="qty[]"></td>
            <td><input type="number" value="${rate}" min="0" oninput="calculate()" name="price[]"></td>
            <td class="amount">0.00</td>
            <td class="text-center">
                <button class="btn btn-danger btn-sm" onclick="removeRow(this)">×</button>
            </td>
        `;
        tbody.appendChild(row);
    } else {
        document
            .querySelectorAll(`[data-item="${name}"]`)
            .forEach(row => row.remove());
    }
    calculate();
}

function addEmptyRow() {
    const tbody = document.querySelector("#invoiceTable tbody");
    const row = document.createElement("tr");

    row.innerHTML = `
        <td><input type="text" placeholder="Custom item" name="itemname[]"></td>
        <td><input type="number" value="1" min="1" oninput="calculate()" name="qty[]"></td>
        <td><input type="number" value="0" min="0" oninput="calculate()" name="price[]"></td>
        <td class="amount">0.00</td>
        <td class="text-center">
            <button class="btn btn-danger btn-sm" onclick="removeRow(this)">×</button>
        </td>
    `;
    tbody.appendChild(row);
}

function removeRow(btn) {
    btn.closest("tr").remove();
    calculate();
}

function calculate() {
    let subtotal = 0;

    document.querySelectorAll("#invoiceTable tbody tr").forEach(row => {
        const qtyInput = row.children[1].querySelector("input");
        const rateInput = row.children[2].querySelector("input");

        if (!qtyInput || !rateInput) return;

        const qty = parseFloat(qtyInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;

        const amount = qty * rate;
        row.querySelector(".amount").innerText = amount.toFixed(2);
        subtotal += amount;
    });

    document.getElementById("subTotal").innerText = subtotal.toFixed(2);
    document.getElementById("grandTotal").innerText = subtotal.toFixed(2);
}
</script>
<script>
function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess,onerror);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		window.location.href='<?php echo base_url('worker/view_edit/invoice/'.$worker_id)?>';
	}
}
function onerror(res){
    console.log(res);
    for(var i in res.errors){
        $('#'+i+'Error').html(res.errors[i]);
        $('[name="'+i+'"]').addClass('invalid');
        $('[data-error-wrapper="'+i+'"]').addClass('invalid_parent');

        const match = i.match(/(itemname|qty|price)\[(\d+)\]/);
        if (match) {
            const field = match[1]; // itemname OR qty
            const index = parseInt(match[2]);

            const inputs = document.querySelectorAll(`[name="${field}[]"]`);
            if (inputs[index]) {
                inputs[index].classList.add('invalid');
                //inputs[index].nextElementSibling.innerText = message;
            }
        }

    }
}
</script>