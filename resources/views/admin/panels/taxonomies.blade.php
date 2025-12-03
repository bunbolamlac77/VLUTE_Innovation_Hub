<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 taxonomies">
  {{-- Faculties --}}
  <div class="card">
    <div class="card-title">Khoa/Đơn vị</div>
    <form class="inline-flex gap-2 mb-3" method="POST" action="{{ route('admin.tax.faculties.store') }}">
      @csrf
      <input class="ipt" name="name" placeholder="Tên khoa..." required>
      <input class="ipt" name="code" placeholder="Mã (tuỳ chọn)">
      <button class="btn btn-primary" type="submit">Thêm</button>
    </form>
    <ul class="list">
      @forelse ($faculties as $f)
        <li class="list-item">
          <span>{{ $f->name }} @if($f->code)<span class="sub">({{ $f->code }})</span>@endif</span>
          <span class="actions">
            <form method="POST" action="{{ route('admin.tax.faculties.update',$f) }}">
              @csrf
              <input class="ipt ipt-sm" name="name" value="{{ $f->name }}" required>
              <input class="ipt ipt-sm" name="code" value="{{ $f->code }}">
              <button class="btn btn-ghost btn-sm" type="submit">Lưu</button>
            </form>
            <form method="POST" action="{{ route('admin.tax.faculties.destroy',$f) }}" onsubmit="return window.confirmDelete(event,'Xoá khoa?')">
              @csrf
              <button class="btn btn-danger btn-sm" type="submit">Xoá</button>
            </form>
          </span>
        </li>
      @empty <li class="empty">Chưa có khoa</li> @endforelse
    </ul>
  </div>

  {{-- Categories --}}
  <div class="card">
    <div class="card-title">Danh mục</div>
    <form class="inline-flex gap-2 mb-3" method="POST" action="{{ route('admin.tax.categories.store') }}">
      @csrf
      <input class="ipt" name="name" placeholder="Tên danh mục..." required>
      <input class="ipt" name="slug" placeholder="Slug (tuỳ chọn)">
      <button class="btn btn-primary" type="submit">Thêm</button>
    </form>
    <ul class="list">
      @forelse ($categories as $c)
        <li class="list-item">
          <span>{{ $c->name }} <span class="sub">({{ $c->slug }})</span></span>
          <span class="actions">
            <form method="POST" action="{{ route('admin.tax.categories.update',$c) }}">
              @csrf
              <input class="ipt ipt-sm" name="name" value="{{ $c->name }}" required>
              <input class="ipt ipt-sm" name="slug" value="{{ $c->slug }}">
              <button class="btn btn-ghost btn-sm" type="submit">Lưu</button>
            </form>
            <form method="POST" action="{{ route('admin.tax.categories.destroy',$c) }}" onsubmit="return window.confirmDelete(event,'Xoá danh mục?')">
              @csrf
              <button class="btn btn-danger btn-sm" type="submit">Xoá</button>
            </form>
          </span>
        </li>
      @empty <li class="empty">Chưa có danh mục</li> @endforelse
    </ul>
  </div>

  {{-- Tags --}}
  <div class="card">
    <div class="card-title">Tags</div>
    <form class="inline-flex gap-2 mb-3" method="POST" action="{{ route('admin.tax.tags.store') }}">
      @csrf
      <input class="ipt" name="name" placeholder="Tên tag..." required>
      <input class="ipt" name="slug" placeholder="Slug (tuỳ chọn)">
      <button class="btn btn-primary" type="submit">Thêm</button>
    </form>
    <ul class="list">
      @forelse ($tags as $t)
        <li class="list-item">
          <span>{{ $t->name }} <span class="sub">({{ $t->slug }})</span></span>
          <span class="actions">
            <form method="POST" action="{{ route('admin.tax.tags.update',$t) }}">
              @csrf
              <input class="ipt ipt-sm" name="name" value="{{ $t->name }}" required>
              <input class="ipt ipt-sm" name="slug" value="{{ $t->slug }}">
              <button class="btn btn-ghost btn-sm" type="submit">Lưu</button>
            </form>
            <form method="POST" action="{{ route('admin.tax.tags.destroy',$t) }}" onsubmit="return window.confirmDelete(event,'Xoá tag?')">
              @csrf
              <button class="btn btn-danger btn-sm" type="submit">Xoá</button>
            </form>
          </span>
        </li>
      @empty <li class="empty">Chưa có tag</li> @endforelse
    </ul>
  </div>
</div>