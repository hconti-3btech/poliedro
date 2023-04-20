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
    public $nv_permissao;

    public $permissoes = [];
    public $historicos = [];

    public $hoje = '';

    public $liberado = false;

    public $msg = '';

    public function render()
    {
        $this->cpfs = DB::table('pessoas')->pluck('cpf')->toArray();

        $historicos = DB::table('fluxos')->latest()->take('20')->get();

        $this->hoje = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

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
        $this->nv_permissao = $pessoa->nv_permissao;

        $permissoes = DB::table('permissoes')->where('id_pessoa',$pessoa->id)->where('vencimento','>=',$this->hoje->format('Y-m-d'))->get();


        //PERMISSAO ALUNO TOTAL
        if ( $this->pessoaTipo == 'ALUNO' && $this->nv_permissao == 'TOTAL' ){
                
            $this->liberado = true;
            $this->msg = '';
        }
        
        //Aluno dia 
        if ( $this->pessoaTipo == 'ALUNO' && $this->nv_permissao == 'DIA' && $this->hoje->format('H:i') >= '06:00' and  $this->hoje->format('H:i') <= '18:00'){
            
            $this->liberado = true;
            $this->msg = '';
        }

        //Aluno Noite 
        if ( $this->pessoaTipo == 'ALUNO' && $this->nv_permissao == 'NOITE' && $this->hoje->format('H:i') >= '18:01' and  $this->hoje->format('H:i') <= '23:00'){
            
            $this->liberado = true;
            $this->msg = '';
        }

        //Funcionario, Visitante, Prestador, Familiar
        if ( $this->pessoaTipo == 'FUNCIONARIO' || $this->pessoaTipo == 'VISITANTE' || $this->pessoaTipo == 'PRESTADOR' || $this->pessoaTipo == 'FAMILIAR'){
            
            $this->liberado = true;
            $this->msg = '';
        }


        foreach ($permissoes as $key => $permissao) {
            
            $usuario = User::find($permissao->id_user_resp);

            $vencimento = DateTime::createFromFormat('Y-m-d H:i:s', $permissao->vencimento);
            $permissao->vencimento = $vencimento->format('d/m/Y');
            $permissao->responsavel = $usuario->name;

            $dif = $this->hoje->diff($vencimento);
            
            //Verifica liberação dia
            if($dif->d == 0){

                $this->liberado = true;
                $this->msg = '';
            }

        }

        $this->permissoes = $permissoes;
    }

    public function liberar()
    {
        if ($this->liberado){

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

                //Limpa tela e valores
                $this->setCPF = '';
                $this->cpfs = '';
                $this->pessoaNome = '';
                $this->pessoaFoto = '4dd77086d7b4212efcd61a3d3e9fdc91.jpg';
                $this->pessoaId = 1;
                $this->pessoaRa = '';
                $this->pessoaTipo = '';
                $this->pessoaCPF = '';
                $this->permissoes = [];
                $this->liberado = false;
                
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
        }else{
            $this->msg = 'USUARIO SEM PERMISSÂO';
        }
        
        
    }
}
