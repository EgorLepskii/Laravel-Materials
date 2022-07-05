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

        <div class="container">
            <h1 class="my-md-5 my-4">Обновить ссылку</h1>
            <div class="row">
                <div class="col-lg-5 col-md-8">
                    <form action="{{route('link.update',['link' => $link->getAttribute('id')])}}" method="post">
                        @csrf
                        <input type="hidden" name="materialId" value="{{$material}}">
                        <input type="hidden" name="linkId" value="{{$link->getAttribute('id')}}">
                        <div class="form-floating mb-3">
                        <div class="form-floating mb-3">
                            <input name = "sign" type="text" value="{{$link->getAttribute('sign')}}" class="form-control" placeholder="Напишите название" id="floatingName">
                            <label for="floatingName">Название</label>
                            @if($errors->any())
                                <div class="invalid-feedback" style="display: block">
                                    {{$errors->first('sign')}}
                                </div>

                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input name = "url" value="{{$link->getAttribute('url')}}" type="text" placeholder="адрес ссылки" class="form-control"  id="floatingAuthor">
                            <label for="floatingAuthor">Адрес</label>
                            @if($errors->any())
                                <div class="invalid-feedback" style="display: block">
                                    {{$errors->first('url')}}
                                </div>

                            @endif
                        </div>

                        <button class="btn btn-primary" type="submit">Обновить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
