@extends('layouts.default')

@section('plugins.WebcamJS', true)

@section('title')
    Pessoas
@endsection

@section('content_header')
    <h1>Atualizar de Pessoas</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="container-fluid">
                @if ($errors->any())
                    @foreach ($errors->all() as $erro)
                        <div class="alert alert-danger"><pre>{{$erro}}</pre></div>    
                    @endforeach
                @endif
                
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Atualizar Pessoa</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('pessoas.update')}}" onsubmit="bloqSubmit()"  enctype="multipart/form-data">
                            @csrf      
                            @method('PUT')
                            <input type="hidden" value="{{$pessoa->id}}" name="id">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Tipo: </span>
                                </div>
                                <select class="custom-select rounded-0" id="tipo" name="tipo">
                                    <option value='ALUNO' {{ old('tipo') == $pessoa->tipo ? 'selected' : ''}}>ALUNO</option>
                                    <option value='FAMILIAR' {{ old('tipo') == $pessoa->tipo ? 'selected' : ''}}>FAMILIAR</option>
                                    <option value='VISITANTE' {{ old('tipo') == $pessoa->tipo ? 'selected' : ''}}>VISITANTE</option>
                                    <option value='FUNCIONARIO' {{ old('tipo') == $pessoa->tipo ? 'selected' : ''}}>FUNCIONARIO</option>
                                    <option value='PRETADOR' {{ old('tipo') ==  $pessoa->tipo ? 'selected' : ''}}>PRETADOR</option>

                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome: </span>
                                </div>
                                <input id="nome" type="text" class="form-control" placeholder="Nome Completo" name="nome" value="{{$pessoa->nome}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">CPF: </span>
                                </div>
                                <input id="cpf" type="text" class="form-control" placeholder="CPF" name="cpf" value="{{$pessoa->cpf}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RA: </span>
                                </div>
                                <input id="ra" type="text" class="form-control" placeholder="Registro do Aluno" name="ra" value="{{$pessoa->ra}}">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Foto: </span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imgFile" accept="image/*" name="imgFile">
                                    <label class="custom-file-label" for="imgFile" >Selecione a imagem</label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Foto Doc: </span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imgFile_doc" accept="image/*" name="imgFile_doc">
                                    <label class="custom-file-label" for="imgFile_doc" >Selecione a imagem</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-success form-control" id="btnSave" >Atualizar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div id="camera"></div>

                        <button id="abrir-camera">Abrir câmera</button>
                        <button id="tirar-foto">Tirar foto</button>

                        <a id="download-link" download="foto.jpg"></a>

                    </div>
                </div>
            </div>
        </div>    
    </div>

    
@endsection

@section('js')
    
    <script>
       

       //WEBCAM
       Webcam.set({
        width: 640,
        height: 480,
        image_format: 'jpeg',
        jpeg_quality: 90
        });

        document.getElementById('abrir-camera').onclick = function() {
            Webcam.attach('#camera');
        };

        document.getElementById('tirar-foto').onclick = function() {
            Webcam.snap(function(data_uri) {
                // Cria um link para download da imagem
                var link = document.getElementById('download-link');
                link.setAttribute('href', data_uri);

                // Define o nome do arquivo a ser salvo
                link.setAttribute('download', 'foto.jpg');

                // Salva a imagem no computador do usuário
                link.click();

                const fotocamera = document.getElementById('camera');
                fotocamera.innerHTML = '';
                fotocamera.style = '';

            });
        };

        //imgagens 
        var div = document.getElementsByClassName("custom-file-label")[0];
        var input = document.getElementById("imgFile");

        div.addEventListener("click", function(){
            input.click();
        });
        input.addEventListener("change", function(){
            var nome = "Não há arquivo selecionado. Selecionar arquivo...";
            if(input.files.length > 0) nome = input.files[0].name;
            div.innerHTML = nome;
        });

        var div_doc = document.getElementsByClassName("custom-file-label")[1];
        var input_doc = document.getElementById("imgFile_doc");

        div_doc.addEventListener("click", function(){
            input_doc.click();
        });
        input_doc.addEventListener("change", function(){
            var nome_doc = "Não há arquivo selecionado. Selecionar arquivo...";
            if(input_doc.files.length > 0) nome_doc = input_doc.files[0].name;
            div_doc.innerHTML = nome_doc;
        });

    // Confirma envio de Formularo
        function confirmar(){
        // só permitirá o envio se o usuário responder OK
        var resposta = window.confirm("Deseja mesmo" + 
                        " enviar o formulário?");
        if(resposta)
            return true;
        else
            return false; 
        }
    </script>
@stop