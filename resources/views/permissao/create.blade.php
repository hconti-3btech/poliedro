@extends('layouts.default')

@section('title')
    Permissão
@endsection

@section('content_header')
    <h1>Permissão de Pessoas</h1>
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
                        <h3 class="card-title">Cadastrar Pessoa</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('permissao.store')}}" onsubmit="bloqSubmit()"  enctype="multipart/form-data">
                            @csrf      
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome: </span>
                                </div>
                                <select class="custom-select rounded-0" id="id_pessoa" name="id_pessoa">
                                    @foreach ($pessoas as $pessoa)
                                        <option value={{$pessoa['id']}} {{ old('id_pessoa') == $pessoa['id'] ? 'selected' : ''}}>{{$pessoa['nome']}}</option>    
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Motivo: </span>
                                </div>
                                <input id="motivo" type="text" class="form-control" placeholder="Motivo" name="motivo">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Vencimento: </span>
                                </div>
                                <input id="vencimento" type="date" class="form-control" placeholder="Vencimento" name="vencimento">
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-success form-control" id="btnSave" >Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>    
    </div>

    
@endsection

@section('js')
    
    <script>

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