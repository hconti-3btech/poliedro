@extends('layouts.default')

@section('plugins.Datatables', true)

@section('title')
    Permissões
@endsection

@section('content_header')
    <h1>Permissões</h1>
@endsection

@section('content')
    @if(session('msg'))
    <div class="alert alert-{{session('type')}}">
        <p>{{session('msg')}}</p>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form method="POS">
                        <div class="row">    
                            <div class="col-12 col-lg-7 mt-2"> 
                                <a href="{{route('permissao.create')}}">
                                    <button type="button" class="btn btn-success"><i class="fas fa-solid fa-plus"></i></button>
                                </a>    
                            </div>
                        </div>        
                    </form>    
                </div>    
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <table id="permissoes" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Pessoa</th>
                                <th>Nivel</th>
                                <th>Vencimento</th>
                                <th>Responsavel</th>
                                <th>Deletar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissoes as $dataValue)
                                <tr>
                                    <td>{{$dataValue['tipo']}} </td>
                                    <td>{{$dataValue['pessoa']}} </td>    
                                    <td>{{$dataValue['nv_permissao']}} </td>
                                    <td>{{$dataValue['vencimento']}} </td>
                                    <td>{{$dataValue['responsavel']}} </td>
                                    <td><a href="{{route('permissao.destroy',["id"=>$dataValue['id']])}}" class="btn btn-danger btn-sm" onclick="return confirmar()">Deletar</a></td>
                                </tr>
                            @endforeach
                        </tbody>   
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        //Datatable
        $(document).ready(function() {
            $('#permissoes').DataTable( {
                "language":{
                    "url":"https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                },
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                "paging": true,
                "info": true,
                "searching": true,
                "dom": 'Bfrtip',
                "pageLength": 50,
                "buttons": ["copy", "csv", "excel", "pdf"],
                "order": [[3, 'asc']],
            })
        });


        //Confirma envio de Formularo
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