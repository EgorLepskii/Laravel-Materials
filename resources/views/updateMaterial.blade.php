<?php

?>

    <!doctype html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-utilities')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>Материалы</title>
</head>
<body>
<div class="main-wrapper">
    <div class="content">
        @include('layouts.header')

        <div class="container">
            <h1 class="my-md-5 my-4">Обновить материал</h1>
            <div class="row">
                <div class="col-lg-5 col-md-8">
                    <form action="{{route('material.update',['material' => $material->getAttribute('id')])}}"
                          method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$material->getAttribute('id')}}">
                        <div class="form-floating mb-3">

                            <select name="type_id" class="form-select" id="floatingSelectType">

                                @if(empty(old('type_id')))

                                    @foreach($types as $type)
                                        @if($type->id == $currentTypeId)
                                            <option value="{{$type->id}}" selected>{{$type->name}}</option>
                                        @else
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endif
                                    @endforeach
                                @else

                                    @foreach($types as $type)

                                        @if($type->id == old('type_id'))
                                            <option value="{{$type->id}}" selected>{{$type->name}}</option>
                                        @else
                                            <option value="{{$type->id}}">{{$type->name}}</option>

                                        @endif
                                    @endforeach

                                @endif
                            </select>
                            <label for="floatingSelectType">Тип</label>
                            @if($errors->any())
                                <div class="invalid-feedback" style="display: block">
                                    {{$errors->first('type_id')}}
                                </div>

                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <select name="category_id" class="form-select" id="floatingSelectCategory">

                                @if(empty(old('category_id')))

                                    @foreach($categories as $category)
                                        @if($category->id == $currentCategoryId)
                                            <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                        @else
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endif
                                    @endforeach
                                @else

                                    @foreach($categories as $category)
                                        @if($category->id == old('category_id'))
                                            <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                        @else
                                            <option value="{{$category->id}}">{{$category->name}}</option>

                                        @endif
                                    @endforeach

                                @endif
                            </select>
                            <label for="floatingSelectCategory">Категория</label>
                            @if($errors->any())
                                <div class="invalid-feedback" style="display: block">
                                    {{$errors->first('category_id')}}
                                </div>

                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input name="name" type="text" value="{{old('name') ?? $material->getAttribute('name')}}"
                                   class="form-control" placeholder="Напишите название" id="floatingName">
                            <label for="floatingName">Название</label>
                            @if($errors->any())
                                <div class="invalid-feedback" style="display: block">
                                    {{$errors->first('name')}}
                                </div>

                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input name="authors" value="{{old('authors') ?? $material->getAttribute('authors')}}" type="text"
                                   class="form-control" id="floatingAuthor">
                            <label for="floatingAuthor">Авторы</label>
                            @if($errors->any())
                                <div class="invalid-feedback" style="display: block">
                                    {{$errors->first('authors')}}
                                </div>

                            @endif
                        </div>
                        <div class="form-floating mb-3">
                    <textarea name="description" class="form-control" placeholder="Напишите краткое описание"
                              id="floatingDescription"
                              style="height: 100px">{{old('description') ?? $material->getAttribute('description')}}</textarea>
                            <label for="floatingDescription">Описание</label>
                            @if($errors->any())
                                <div class="invalid-feedback" style="display: block">
                                    {{$errors->first('description')}}
                                </div>

                            @endif
                        </div>
                        <button class="btn btn-primary" type="submit">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer py-4 mt-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col text-muted">Test</div>
            </div>
        </div>
    </footer>
</div>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
<script src="{{asset('js/Ajax.js')}}"></script>
<script src="{{asset('js/deleteConfirmation.js')}}"></script>
</body>
</html>
