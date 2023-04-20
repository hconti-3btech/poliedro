<div id='componet'>
    <div class="row">
        <div class="col-6">
            @if ($msg!= '')
                <div class="alert alert-danger"><pre>{{$msg}}</pre></div>    
            @endif
            <div class="card">
                <div class="card-header p-1">
                    <form method="POS">
                        <div class="row">
                            <div class="col-8">
                                <select class="js-example-basic-single js-states form-control" id="select2" >
                                    <option value="">-- CPF --</option>
                                    @foreach($cpfs as $cpf)
                                        <option value="{{ $cpf }}">{{ $cpf }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <a class="ml-2" href="{{route('pessoas.create')}}">
                                    <button type="button" class="btn  btn-success">
                                        <i class="fas fa-solid fa-plus"></i>
                                    </button>
                                </a>
                                {{-- <a class="ml-2" href="#">
                                    <button type="button" class="btn  btn-primary"><i
                                            class="fas fa-solid fa-door-open"></i></button>
                                </a> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dados</h3>
                </div>
                <div class="card-body p-1 mt-1">
                    <div class="col-12 d-flex align-items-stretch flex-column">
                        <div class="card bg-light d-flex flex-fill">
                            {{-- <div class="card-header text-muted border-bottom-0" style="font-size: 20pt">
                                
                            </div> --}}
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-7 p-3">
                                        <h3 class="lead">Tpo : {{ $pessoaTipo }}</h3>
                                        <h3 class="lead">Permissão :{{ $nv_permissao }}</h3>
                                        <hr>
                                        <h1 class="lead"><b>{{ $pessoaNome  }}</b></h1>
                                        <p class="text-muted text-lg"><b>{{ $pessoaRa  }}</b></p>
                                        <p class="text-muted text-lg"><b>{{$pessoaCPF}}</b></p>
                                    </div>
                                    <div class="col-5 p-3">
                                        <a href="{{asset('storage/img/pessoas/'.$pessoaFoto)}}" data-toggle="lightbox" >
                                            <img src="{{asset('storage/img/pessoas/'.$pessoaFoto)}}" alt="user-avatar" class="img-square img-fluid">
                                        </a>

                                        
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <button wire:click="liberar()" class="btn btn-success btn-md">
                                        <i class="fas fa-solid fa-check"></i>
                                        {{-- <i class="fas fa-solid fa-ban"></i> --}}
                                    </button>
                                    <a href="{{route('pessoas.edit',["id"=>$pessoaId])}}" class="btn btn-md btn-primary">
                                        <i class="fas fa-user"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permissões</h3>
                </div>
                <div class="card-body p-1 mt-1" style="height: 365px;">
                    <table id="permissoes" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Vencimento</th>
                                <th>Motivo</th>
                                <th>Responsavel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissoes as $dataValue)
                                <tr>
                                    <td>{{$dataValue->vencimento}} </td>
                                    <td>{{$dataValue->motivo}} </td>
                                    <td>{{$dataValue->responsavel}} </td>
                                </tr>
                            @endforeach
                        </tbody>   
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Historico</h3>
                </div>
                <div class="card-body p-1 mt-1">
                    <table id="permissoes" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Responsavel</th>
                                <th>Sentido</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historicos as $historico)
                                <tr>
                                    <td>{{$historico->nome}} </td>
                                    <td>{{$historico->responsavel}} </td>
                                    <td>{{$historico->sentido}} </td>
                                    <td>{{$historico->data}} </td>
                                </tr>
                            @endforeach
                        </tbody>   
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#select2').select2();

            $('#select2').on('change', function (e) {
                var data = $('#select2').select2("val");
                @this.set('setCPF', data);
            });

            window.livewire.on('data-change-event',()=>{
                $('#select2').select2({
                    // theme: "classic",
                    closeOnSelect: true
                });
            })
        });
    </script>

     {{-- Galery --}}
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" integrity="sha512-Y2IiVZeaBwXG1wSV7f13plqlmFOx8MdjuHyYFVoYzhyRr3nH/NMDjTBSswijzADdNzMyWNetbLMfOpIPl6Cv9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

     {{-- Gallery --}}
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
          });
    </script>
@endpush