@extends('dashboard.layouts.main')

@section('container')
<div class="container-fluid ml-2">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ubah Posts</h1>

    <form method="post" action="/dashboard/posts/{{ $post->slug }}" class="col-sm-8 mb-5" enctype="multipart/form-data">
      @method('put')  
      @csrf
        <div class="mb-3">
          <label for="title" class="form-label">Judul</label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" autofocus required value="{{ old('title', $post->title) }}">
          @error('title')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
        </div>
        <div class="mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" required value="{{ old('slug', $post->slug) }}">
          @error('slug')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
        </div>
        <div class="mb-3">
          <label for="category" class="form-label">Kategori</label>
          <select class="form-select" name="category_id">
            @foreach ($categories as $category)
            @if(old('category_id', $post->category_id) == $category->id)
            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
            @else
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Gambar</label>
          <input type="hidden" name="oldImage" value="{{ $post->image }}">
          @if($post->image)
          <img src="{{ asset('storage/' .$post->image) }}" class="img-preview img-fluid mb-3 col-sm-5 d-block">
          @else
          <img class="img-preview img-fluid mb-3 col-sm-5 d-block">
          @endif
          <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" onchange="previewImage()">
          @error('image')@extends('dashboard.layouts.main')

          @section('container')
          <div class="container-fluid ml-2">
          
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Posts Saya</h1> 
          
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show col-lg-8" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
          
            <a href="/dashboard/posts/create" class="btn btn-primary mb-3">Tambah Data</a>
              <table class="table table-hover table-sm col-lg-8 ">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($posts as $post)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category->name }}</td>
                    <td>
                      <a href="/dashboard/posts/{{ $post->slug }}" class="badge bg-info"><i class="fa fa-thin fa-eye"></i></a>
                      <a href="/dashboard/posts/{{ $post->slug }}/edit" class="badge bg-warning"><i class="fas fa-sharp fa-light fa-pen"></i></a>
                      <form action="/dashboard/posts/{{ $post->slug }}" method="post" class="d-inline">
                      @method('delete')
                    @csrf
                    <button class="badge bg-danger border-0" onclick="return confirm('Yakin ingin menghapus data?')"><i class="fa fa-light fa-trash"></i></button>
                  </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endsection
          <div class="invalid-feedback">
            {{ $message }}
          </div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="body" class="form-label">Body</label>
          @error('body')
          <p class="text-danger">{{ $message }}</p>
          @enderror
          <input id="body" type="hidden" name="body" value="{{ old('body', $post->body) }}">
          <trix-editor input="body"></trix-editor>
        </div>


        <button type="submit" class="btn btn-primary">Update Post</button>
      </form>
    </div>

    {{-- auto slug --}}
    <script>
        const title = document.querySelector('#title');
        const slug = document.querySelector('#slug');

        title.addEventListener('change', function() {
           fetch('/dashboard/posts/checkSlug?title=' + title.value)
            .then(response => response.json())
            .then(data => slug.value = data.slug )
        })

        document.addEventListener('trix-file-accept', function(e) {
            e.preventDefault();
        })

        function previewImage(){
          const image = document.querySelector('#image');
          const imgPreview = document.querySelector('.img-preview');

          imgPreview.style.dispaly = 'block';

          const oFReader = new FileReader();
          oFReader.readAsDataURL(image.files[0]);
          
          oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
          }
        }
    </script>

@endsection