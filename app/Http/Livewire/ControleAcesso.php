<?php

namespace App\Http\Livewire;

use App\Models\Fluxo;
use App\Models\Pessoas;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ControleAcesso extends Component
{
    public $setCPF = '';
    
    public $cpfs;

    public $pessoaNome;
    public $pessoaFoto = '4dd77086d7b4212efcd61a3d3e9fdc91.jpg';
    public $pessoaId = 1;
    public $pessoaRa;
    public $pessoaTipo;
    public $pessoaCPF;

    public $permissoes = [];
    public $historicos = [];

    public function render()
    {
        $this->cpfs = DB::table('pessoas')->pluck('cpf')->toArray();

        $historicos = DB::table('fluxos')->latest()->take('20')->get();

        foreach ($historicos as $key => $historico) {

            $hitoricoPessoa = Pessoas::find($historico->id_pessoa);
            $historico->nome = $hitoricoPessoa->nome;

            $historicoResponsavel = User::find($historico->id_user_resp);
            $historico->responsavel = $historicoResponsavel->name;

            $historicoData = DateTime::createFromFormat('Y-m-d H:i:s', $historico->created_at);
            $historico->data = $historicoData->format('d/m/Y H:i');
        }
        $this->historicos = $historicos;

        return view('livewire.controle-acesso')->extends('layouts.app');
    }

    public function hydrate()
    {
        $this->emit('data-change-event');
    }

    public function updated($setCPF)
    {
        $pessoa = DB::table('pessoas')->where('cpf',$this->setCPF)->first();

        $this->pessoaId = $pessoa->id;
        $this->pessoaRa = $pessoa->ra;
        $this->pessoaTipo = $pessoa->tipo;
        $this->pessoaNome = $pessoa->nome;
        $this->pessoaFoto = $pessoa->foto;
        $this->pessoaCPF = $pessoa->cpf;

        $hoje = date('Y-m-d 00:00:00');
        $permissoes = DB::table('permissoes')->where('id_pessoa',$pessoa->id)->where('vencimento','>=',$hoje)->get();

        foreach ($permissoes as $key => $permissao) {
            
            $usuario = User::find($permissao->id_user_resp);

            $vencimento = DateTime::createFromFormat('Y-m-d H:i:s', $permissao->vencimento);
            $permissao->vencimento = $vencimento->format('d/m/Y');
            $permissao->responsavel = $usuario->name;
        }

        $this->permissoes = $permissoes;
    }

    public function liberar()
    {
        $pessoaExiste = Pessoas::find($this->pessoaId);

        if (!empty($pessoaExiste)){

            $user = Auth::user();

            $sentido = Fluxo::where('id_pessoa', $this->pessoaId)->latest()->first();

            if (empty($sentido)){

                $sentido = 'ENTRADA';

            }else{

                if ($sentido->sentido == 'ENTRADA'){
                    $sentido = 'SAIDA';
    
                }else{
                    $sentido = 'ENTRADA';
                }
            }

            $fluxo = new Fluxo();

            $fluxo->id_pessoa  = $this->pessoaId;
            $fluxo->id_user_resp = $user->id;
            $fluxo->sentido = $sentido;
            $fluxo->save();    


            $this->setCPF = '';
            $this->cpfs = '';
            $this->pessoaNome = '';
            $this->pessoaFoto = '4dd77086d7b4212efcd61a3d3e9fdc91.jpg';
            $this->pessoaId = 1;
            $this->pessoaRa = '';
            $this->pessoaTipo = '';
            $this->pessoaCPF = '';
            $this->permissoes = [];
            
            $historicos = DB::table('fluxos')->latest()->take('20')->get();

            foreach ($historicos as $key => $historico) {
                $hitoricoPessoa = Pessoas::find($historico->id_pessoa);
                $historico->nome = $hitoricoPessoa->nome;

                $historicoResponsavel = User::find($historico->id_user_resp);
                $historico->responsavel = $historicoResponsavel->name;

                $historicoData = DateTime::createFromFormat('Y-m-d H:i:s', $historico->created_at);
                $historico->data = $historicoData->format('d/m/Y H:i');
            }
            $this->historicos = $historicos;

        }
        
    }
}
