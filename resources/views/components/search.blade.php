<div class="search-box">
    <div class="row">
        <div class="col-sm-3 pb-2">
            <select id="ddlAnimal" data-placeholder="食材" class="chosen-select form-control" tabindex="2">

            </select>
        </div>
        <div class="col-sm-3 pb-2">
            <select id="ddlPref" class="custom-select" data-placeholder="地域" >
              <option value="" selected>地域</option>
                @foreach ($prefList as $pref)
                <option value="{{ $pref->pref }}">{{ $pref->pref }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 pb-2">
            <select id="ddlCity" data-placeholder="詳細" class="chosen-select form-control" tabindex="2">
            </select>
        </div>
        
        <div class="col-sm-2 pb-2">
            <select id="ddlPaymentType" data-placeholder="販売形式" class="chosen-select form-control" tabindex="2">
            </select>
        </div>
      
        <div class="col-sm-1">
            <button type="button" class="btn btn-danger form-control-lg btn-search">検索</button>
        </div>
    </div>
</div>
