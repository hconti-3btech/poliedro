<?php

namespace App\Http\Controllers;

use App\Models\Permissoes;
use App\Models\Pessoas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissoes = Permissoes::all();

        foreach ($permissoes as $key => $permissao) {

            $pessoa = Pessoas::find($permissao->id_pessoa);
            $permissao->tipo = $pessoa->tipo;
            $permissao->pessoa = $pessoa->nome;

            $user = User::find($permissao->id_user_resp);
            $permissao->responsavel = $user->name;
           
        }

        return view('permissao.index')
        ->with([
            'permissoes' => $permissoes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $valor = session()->get('valor');
        
        if (isset($valor)){
            $pessoas = Pessoas::where('id',$valor)->get();
        }else{
            $pessoas = Pessoas::all();
        }

        return view('permissao.create')
        ->with([
            'pessoas' => $pessoas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $mensagens = [
            'vencimento.required' => 'O Vencimento é obrigatório!',
            'nv_permissao.required' => 'O Nivel de permissão é obrigatório!',
            'id_pessoa.required' => 'A Pessoa é obrigatório!',
        ];

        $request->validate([
            'id_pessoa' =>  'required|exists:pessoas,id',
            'nv_permissao'     =>  'required',
            'vencimento' => 'required',
        ],$mensagens);

        $permissao = new Permissoes;

        $permissao->id_pessoa = $request->id_pessoa;
        $permissao->nv_permissao = $request->nv_permissao;
        $permissao->vencimento = $request->vencimento;
        $permissao->motivo = $request->motivo;
        $permissao->id_user_resp = $user->id;
        $permissao->save();
        
        return redirect()
            ->route('home')
            ->with(['msg'   =>  'Permissão CADASTRADA com sucesso',
                    'type'  =>  'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permissao = Permissoes::find($id);

        if (empty($permissao)){
            return redirect()->route('permissao.index');
        }
        
        $permissao->delete();

        return redirect()
        ->route('permissao.index')
        ->with([
            'msg'   =>  'Permissao DELETADO com sucesso',
            'type'  =>  'success'
        ]);
    }
}
