@section('title', $title)
@section('description', $description)
@extends('layout.app')
@section('style')
<link rel="stylesheet" href="https://taufik-nurrohman.js.org/color-picker/index.min.css">
<style>
  .colorpicker {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 100%;
  }
</style>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="d-flex align-items-center user-member__title mb-30 mt-30">
        <h4 class="text-capitalize">{{ trans('edit settings') }}</h4>
      </div>
    </div>
  </div>
  <div class="card mb-50">
    <div class="row">
      <div class="col-12 p-3 px-5">
        <form action="{{ route('settings.colors.update') }}" method="POST">
          @csrf

          <div class="row">
            <!-- Text Color -->
            <div class="col-6 mb-3">
              <label for="colorPrimary">{{ trans('Primary Color') }}</label>
              <input type="text" id="colorPrimary" name="colorPrimary"
                value="{{ $settings['colorPrimary'] ?? 'rgb(0,0,0)' }}" class="form-control colorpicker" />
            </div>

            <!-- Text Color RGBA -->
            <div class="col-6 mb-3">
              <label for="colorPrimaryRgba">{{ trans('Color Primary') }}</label>
              <input type="text" id="colorPrimaryRgba" name="colorPrimaryRgba"
                value="{{ $settings['colorPrimaryRgba']?? 'rgb(0,0,0)' }}" class=" form-control colorpicker" />
            </div>

            <!-- Background Primary Hover -->
            <div class="col-6 mb-3">
              <label for="bgPrimaryHover">{{ trans('Background Primary Hover') }}</label>
              <input type="text" id="bgPrimaryHover" name="bgPrimaryHover"
                value="{{ $settings['bgPrimaryHover']?? 'rgb(0,0,0)' }}" class=" form-control colorpicker" />
            </div>

            <!-- Header Background -->
            <div class="col-6 mb-3">
              <label for="headerBg">{{ trans('Header Background') }}</label>
              <input type="text" id="headerBg" name="headerBg" value="{{ $settings['headerBg']?? 'rgb(0,0,0)' }}" class=" form-control
                colorpicker" />
            </div>

            <!-- Logo Wrapper Background -->
            <div class="col-6 mb-3">
              <label for="logoWrapperBg">{{ trans('Logo Wrapper Background') }}</label>
              <input type="text" id="logoWrapperBg" name="logoWrapperBg"
                value="{{ $settings['logoWrapperBg']?? 'rgb(0,0,0)' }}" class="
                form-control colorpicker" />
            </div>

            <!-- Submit Button -->
            <div class="col-12 mb-3">
              <label>&nbsp;</label>
              <button type="submit"
                class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>

            </div>
            <div class="col-12 mb-3">

              <button type="button" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm"
                onclick="applyColors()">Apply</button>

            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Include JavaScript to initialize Bootstrap Colorpickers -->

@endsection
@section('scripts')

<script src="https://taufik-nurrohman.js.org/color-picker/index.min.js"></script>
<script>
  $(document).ready(function() {
    $('.colorpicker').each(function() {
      var inputValue2 = '';
      try {
        inputValue2 = 'rgb('+$(this).attr('value').match(/\d{1,3},\s?\d{1,3},\s?\d{1,3}/)+')';
    } catch (error) {
        // Code to handle the exception
        inputValue2 = 'rgb(0,0,0)';
    }
      $(this).attr('value',inputValue2);
      $(this).val(inputValue2);
      $(this).css('background-color', inputValue2);
      $(this).css('color', inputValue2);
    });

    // Select all input elements using jQuery
    $('.colorpicker').each(function() {
      const picker = new CP(this);
      picker.on('change', function (r, g, b, a) {
        var inputValue ='';
        if (1 === a) {
          inputValue = 'rgb(' + r + ', ' + g + ', ' + b + ')';
        } else {
          inputValue =  'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';
        }
          if(r == 0 && g == 0 && b == 0){

          }else{
            this.source.value = inputValue;
            $(this.source).css('background-color', inputValue);
            $(this.source).css('color', inputValue);
            var arr = {
              'colorPrimary'      :'--color-primary',
              'colorPrimaryRgba'  :'--color-primary-rgba',
              'bgPrimaryHover'    :'--bg-primary-hover',
              'headerBg'          :'--header-bg',
              'logoWrapperBg'     :'--logo-wrapper-bg',
            };

             // Update CSS variables with new values

             if(this.source.id == 'colorPrimaryRgba'){
              var rgbValues = $('#colorPrimaryRgba').val().match(/\d{1,3},\s?\d{1,3},\s?\d{1,3}/);
             $(':root').css('--color-primary-rgba', rgbValues); // Update with the appropriate value
             }else{
              $(':root').css(arr[this.source.id], $('#' + this.source.id).val());
             }

          }


      });
    });



  });

  $(document).ready(function() {
    $('.colorpicker').each(function() {
      $(this).val('rgb('+$(this).attr('value').match(/\d{1,3},\s?\d{1,3},\s?\d{1,3}/)+')');
      var inputValue = $(this).val();
      $(this).css('background-color', inputValue);
      $(this).css('color', inputValue);
    });
    $('.colorpicker').on('change', function() {
      var inputValue = $(this).val();
      $(this).css('background-color', inputValue);
    });
  });
  function applyColors() {
    $('.colorpicker').each(function() {
      var inputValue = $(this).val();
      $(this).css('background-color', inputValue);
      $(this).css('color', inputValue);
       // Update CSS variables with new values
       $(':root').css('--color-primary', $('#colorPrimary').val());
       var rgbValues = $('#colorPrimaryRgba').val().match(/\d{1,3},\s?\d{1,3},\s?\d{1,3}/);
       $(':root').css('--color-primary-rgba', rgbValues); // Update with the appropriate value
       $(':root').css('--bg-primary-hover', $('#bgPrimaryHover').val()); // Update with the appropriate value
       $(':root').css('--header-bg', $('#headerBg').val()); // Update with the appropriate value
       $(':root').css('--logo-wrapper-bg', $('#logoWrapperBg').val()); // Update with the appropriate value

    });
  }
</script>
@endsection