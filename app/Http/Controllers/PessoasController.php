<?php

namespace App\Http\Controllers;

use App\Models\Permissoes;
use App\Models\Pessoas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PessoasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $data = Pessoas::all();

        return view('pessoas.index')
        ->with([
            'data' => $data,
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pessoas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $mensagens = [
            'required' => 'O :attribute é obrigatório!',
            'min' => 'O :attribute deve ter no minimo 3 caracteres',
            'max' => 'O :attribute deve ter no maximo 100 caracteres',
        ];

        $request->validate([
            'nome'          =>  'required|max:100|min:3',
            'cpf'     =>  'required'
        ],$mensagens);


        // Deixa valor em branco caso nao faça o upload
        $imageName = '';
        
        $pessoa = new Pessoas();

        $pessoa->tipo = $request->tipo;
        $pessoa->nome = $request->nome;
        $pessoa->cpf = $request->cpf;
        $pessoa->ra = $request->ra;
        $pessoa->ra = $request->ra;
        $pessoa->nv_permissao = $request->nv_permissao;

        // Img foto
        if($request->hasFile('imgFile')!=null && $request->file('imgFile')->isValid()){

            $requestImage = $request->imgFile;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(storage_path('app/public/img/pessoas'), $imageName);

        }
        $pessoa->foto = $imageName;

        // Img foto
        if($request->hasFile('imgFile_doc')!=null && $request->file('imgFile_doc')->isValid()){

            $requestImage = $request->imgFile_doc;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(storage_path('app/public/img/documentos'), $imageName);

        }
        $pessoa->foto_doc = $imageName;

        $pessoa->save();

        session()->put('valor', $pessoa->id);

        return redirect()
            ->route('home')
            ->with(['msg'   =>  'Pessoa CADASTRADA com sucesso',
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
    public function edit($id)
    {
        
        $pessoa = Pessoas::find($id);

        if (empty($pessoa)){
            return redirect()->route('pessoas.index');
        }

        return view('pessoas.edit')
        ->with([
            'pessoa' => $pessoa,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id'    =>  'required|exists:pessoas',
            'nome'  =>  'required|max:100|min:3',
            'cpf'   =>  'required',
        ]);

        // Deixa valor em branco caso nao faça o upload
        $imageName = '';
        
        $pessoa = Pessoas::find($request->id);

        $pessoa->tipo = $request->tipo;
        $pessoa->nome = $request->nome;
        $pessoa->cpf = $request->cpf;
        $pessoa->ra = $request->ra;


        // Img foto
        if($request->hasFile('imgFile')!=null && $request->file('imgFile')->isValid()){

            $requestImage = $request->imgFile;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(storage_path('app/public/img/pessoas'), $imageName);

            $pessoa->foto = $imageName;

        }
        
        // Img foto
        if($request->hasFile('imgFile_doc')!=null && $request->file('imgFile_doc')->isValid()){

            $requestImage = $request->imgFile_doc;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(storage_path('app/public/img/documentos'), $imageName);

            $pessoa->foto_doc = $imageName;

        }
        
        $pessoa->update();

        return redirect()
        ->route('home')
        ->with([
            'msg'   =>  'Pessoa ATUALIZADA com sucesso',
            'type'  =>  'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pessoa = Pessoas::find($id);

        if (empty($pessoa)){
            return redirect()->route('pessoas.index');
        }
        
        Storage::delete('public/img/pessoas/'.$pessoa->foto);
        Storage::delete('public/img/documentos/'.$pessoa->foto_doc);


        $permissoes = Permissoes::where('id_pessoa', $pessoa->id)->get();

        foreach ($permissoes as $key => $permissao) {
            $permissao->delete();
        }

        $pessoa->delete();

        return redirect()
        ->route('pessoas.index')
        ->with([
            'msg'   =>  'Pessoa DELETADO com sucesso',
            'type'  =>  'success'
        ]);
    }
    
}