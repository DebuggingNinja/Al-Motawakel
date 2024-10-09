<form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="edit-profile__body">
    <input type="hidden" name="type" value="Payment from Client">
    <input type="hidden" name="client_id" id="financialModalClientId" value="">

    <div class="form-group mb-25">
      <label for="description" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.description') }}
        <span class="text-danger">*</span></label>
      <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="description"
             value="{{ old('description') }}" id="description" placeholder="Description">
      @if ($errors->has('description'))
        <p class="text-danger">{{ $errors->first('description') }}</p>
      @endif
    </div>
    <div class="form-group mb-25">
      <label for="currency" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.currency') }} <span
          class="text-danger">*</span></label>
      <select class="form-select ih-medium ip-gray radius-xs b-light px-15" name="currency" id="currency">
        <option value="rmb" {{ (old('currency')=='rmb' ) ? 'selected' : '' }}>RMB</option>
        <option value="usd" {{ (old('currency')=='usd' ) ? 'selected' : '' }}>USD</option>
      </select>
      @if ($errors->has('currency'))
        <p class="text-danger">{{ $errors->first('currency') }}</p>
      @endif
    </div>
    <div class="form-group mb-25">
      <label for="amount" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.amount') }} <span
          class="text-danger">*</span></label>
      <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="amount" id="amount"
             value="{{ old('amount') }}" step="0.01">
      @if ($errors->has('amount'))
        <p class="text-danger">{{ $errors->first('code') }}</p>
      @endif
    </div>
    <div class="form-group mb-25" style="display: none">
      <label for="rate" class="color-dark fs-14 fw-500 align-center">{{ trans('expense.rate') }}
        |<span class="text-danger">{{ __('expense.Required if currency is: USD') }}</span>
      </label>
      <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="rate" id="rate"
             value="{{ old('rate') }}" step="0.01">
      @if ($errors->has('rate'))
        <p class="text-danger">{{ $errors->first('rate') }}</p>
      @endif
    </div>
    <div class="form-group mb-25" style="display: none">
      <label for="otherCurrency" class="color-dark fs-14 fw-500 align-center">
        {{ trans('expense.amount') }}
      </label>
      <input type="number" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="otherCurrency"
             id="otherCurrency" step="0.01" readonly>
    </div>

    <div class="form-group mb-25">
      <label for="date" class="color-dark fs-14 fw-500 align-center">
        <span class="text-danger">*</span>
        {{ trans('date') }}
      </label>
      <input type="date" class="form-control ih-medium ip-gray radius-xs b-light px-15" name="date" id="date"
             value="{{ date('Y-m-d') }}">
      @if ($errors->has('date'))
        <p class="text-danger">{{ $errors->first('date') }}</p>
      @endif
    </div>


    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
      <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
    </div>

  </div>
</form>
