<div class="search-box">
    <div class="row">
        <div class="col-sm-4 pb-2">
            <select id="ddlAnimal" data-placeholder="食材" class="chosen-select form-control" tabindex="2">

            </select>
        </div>
        <div class="col-sm-3 pb-2">
            <select id="ddlPref" class="custom-select">
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
        <!--
        <div class="col-sm-2 pb-2">
            <select id="ddlType" data-placeholder="ジャンル" class="chosen-select form-control" tabindex="2">
                <option value="">例：日本料理</option>
            </select>
        </div>
      -->
        <div class="col-sm-2">
            <button type="button" class="btn btn-danger form-control-lg w-100 btn-search">検索</button>
        </div>
    </div>
</div>
