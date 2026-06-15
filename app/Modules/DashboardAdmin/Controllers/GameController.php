<?php

namespace Modules\DashboardAdmin\Controllers;

use App\Controllers\BaseController;
use Modules\DashboardAdmin\Models\GameModel;

class GameController extends BaseController
{
    public function index()
    {
        $gameModel = new GameModel();
        return view('Modules\DashboardAdmin\Views\game', [
            'games' => $gameModel->orderBy('nama_game', 'ASC')->paginate(10, 'games'),
            'pager' => $gameModel->pager
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_game' => 'required|max_length[100]',
            'gambar'    => 'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $img = $this->request->getFile('gambar');
        $imgName = $img->getRandomName();
        $img->move('images', $imgName);

        $gameModel = new GameModel();
        $gameModel->insert([
            'nama_game' => $this->request->getPost('nama_game'),
            'gambar'    => $imgName
        ]);

        return redirect()->to('/dashboard/admin/games')->with('success', 'Game berhasil ditambahkan.');
    }

    public function update($id)
    {
        $gameModel = new GameModel();
        $game = $gameModel->find($id);

        if (! $game) {
            return redirect()->back()->with('error', 'Game tidak ditemukan.');
        }

        $rules = [
            'nama_game' => 'required|max_length[100]',
            'gambar'    => 'permit_empty|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_game' => $this->request->getPost('nama_game')
        ];

        $img = $this->request->getFile('gambar');
        if ($img->isValid() && ! $img->hasMoved()) {
            $imgName = $img->getRandomName();
            $img->move('images', $imgName);
            $data['gambar'] = $imgName;

            if (file_exists('images/' . $game['gambar'])) {
                @unlink('images/' . $game['gambar']);
            }
        }

        $gameModel->update($id, $data);

        return redirect()->to('/dashboard/admin/games')->with('success', 'Game berhasil diperbarui.');
    }

    public function delete($id)
    {
        $gameModel = new GameModel();
        $game = $gameModel->find($id);

        if ($game) {
            if (file_exists('images/' . $game['gambar'])) {
                @unlink('images/' . $game['gambar']);
            }
            $gameModel->delete($id);
        }

        return redirect()->to('/dashboard/admin/games')->with('success', 'Game berhasil dihapus.');
    }
}
