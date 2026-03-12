<?php
//
//namespace App\Livewire\App\Permohonan;
//
//use Livewire\Component;
//use App\Models\PromotionApplication;
//use App\Models\SelectJawatan;
//use App\Models\Pemohon;
//use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
//
//class Create extends Component
//{
//    public $selected_jawatan_id = null;
//    public $selected_gred = null;
//    public $jawatanOptions = [];
//    public $kelayakan = null;
//    public $showKelayakan = false;
//
//    public function mount()
//    {
//        $this->loadJawatanOptions();
//    }
//
//    private function loadJawatanOptions()
//    {
//        // Load jawatan dengan kelayakan
//        $this->jawatanOptions = SelectJawatan::with('kelayakan')
//            ->orderBy('nama_jawatan')
//            ->get()
//            ->map(fn ($j) => [
//                'value' => $j->kodJawatan,
//                'gred' => $j->gredJawatan,
//                'label' => $j->display_name,
//                'has_kelayakan' => $j->hasKelayakan(),
//                'kelayakan' => $j->kelayakan
//            ])
//            ->toArray();
//    }
//
//    public function updatedSelectedJawatanId($value)
//    {
//        $selected = collect($this->jawatanOptions)->firstWhere('value', $value);
//        $this->selected_gred = $selected['gred'] ?? null;
//
//        // Check kelayakan
//        if ($selected && $selected['has_kelayakan']) {
//            $staff = Pemohon::with([
//                'akademikStaf',
//                'penerbitan',
//                'penyeliaan',
//                'markahTerkini'])
//                ->where('staff_id', auth()->user()->staff_id)
//                ->first();
//
//            $this->kelayakan = $selected['kelayakan']->checkKelayakan($staff);
//            $this->showKelayakan = true;
//        } else {
//            $this->kelayakan = null;
//            $this->showKelayakan = false;
//        }
//    }
//
//    protected $rules = [
//        'selected_jawatan_id' => 'required',
//        'selected_gred' => 'required'
//    ];
//
//    public function submit()
//    {
//        $this->validate();
//
//        try {
//            $user = auth()->user();
//            $staffId = $user?->staff_id;
//
//            if (blank($staffId)) {
//                throw new \RuntimeException('Akaun ini belum mempunyai Staff ID.');
//            }
//
//            // Generate reference no
//            $referenceNo = 'PA-' . now()->format('Ymd') . '-' . strtoupper(Str::ulid());
//
//            // Create application
//            PromotionApplication::create([
//                'staff_id' => $staffId,
//                'gred_jawatan' => $this->selected_gred,
//                'reference_no' => $referenceNo,
//                'status' => 'DIHANTAR',
//                'is_active' => 1,
//                'metadata' => json_encode([
//                    'jawatan_id' => $this->selected_jawatan_id,
//                    'gred' => $this->selected_gred,
//                    'kelayakan' => $this->kelayakan
//                ])
//            ]);
//
//            session()->flash('success', 'Permohonan berjaya dihantar!');
//            return redirect()->route('app.permohonan.index');
//
//        } catch (\Exception $e) {
//            session()->flash('error', 'Ralat: ' . $e->getMessage());
//        }
//    }
//
//    public function render()
//    {
//        return view('livewire.app.permohonan.create');
//    }
//}
