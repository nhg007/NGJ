<div class="row">
        <div class="col-sm-2 py-2">
            <select id="ddlAnimal" data-placeholder="食材" class="chosen-select form-control" tabindex="2">
            </select>
        </div>
        <div class="col-sm-2 py-2">
        
            <select id="ddlPref" class="chosen-select form-control" data-placeholder="地域" tabindex="2">
              <option value="" selected>地域</option>
                @foreach ($prefList as $pref)
                <option value="{{ $pref->pref }}">{{ $pref->pref }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2 py-2">
            <select id="ddlCity" data-placeholder="詳細" class="chosen-select form-control" tabindex="2">
            </select>
        </div>
        <div class="col-sm-2 py-2">
            <select id="ddlPaymentType" data-placeholder="販売形式" class="chosen-select form-control" tabindex="2">
            </select>
        </div>
        <div class="col-sm-1 py-2">
            <button type="button" class="btn btn-danger form-control-lg w-100 btn-search">検索</button>
        </div>
        <div id="btnBack" class="col-sm-1 py-2" style="display: none">
            <button type="button" class="btn btn-outline-primary form-control-lg w-100">戻る</button>
        </div>
        @if(\Illuminate\Support\Facades\Auth::check())
          <div class="col-sm-1 py-2">
            <i class='fas fa-shopping-cart fa-2x'></i> <span id="badge" class="badge badge-danger" style="display: none">0</span>
          </div>
          <div class="col-sm-2 py-2">
          <a class="btn btn-success" href="/dashboard">マイページ</a>
        </div>
        @else
        <div class="col-sm-2 pt-2">
            <a class="btn btn-outline-success btn" href="/login">美食家ログイン</a>
          </div>
        @endif
    </div>
