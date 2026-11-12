<?php

namespace App\Livewire\Production;

use App\Models\Project;
use App\Models\State;
use App\Services\AutoFillService;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductionCreate extends Component
{
    public $project;

    public $bibliometric;

    public $repositories;

    public $years = [];

    public $states = [];

    public $author = [];

    public $authors_display = [];

    #[Validate('nullable|url')]
    public $url;

    #[Validate('nullable|string|in_array:bibliometric.repositories.*')]
    public $repository;

    #[Validate('required|string')]
    public $title;

    #[Validate('required|integer|digits:4|in_array:years.*')]
    public $year;

    #[Validate('required|array')]
    public $authors = [];

    #[Validate('required|string|in_array:bibliometric.types.*')]
    public $type;

    #[Validate('required|string|in_array:bibliometric.languages.*')]
    public $language;

    #[Validate('required|array')]
    public $searched_terms;

    #[Validate('nullable|string')]
    public $institution;

    #[Validate('nullable|string')]
    public $program;

    #[Validate('nullable|string')]
    public $city;

    #[Validate('nullable|integer|in_array:states.*.id')]
    public $state_id;

    #[Validate('nullable|string|max:60')]
    public $country = 'Brasil';

    #[Validate('nullable|string')]
    public $periodical;

    #[Validate('nullable|string')]
    public $doi;

    public $keywords;

    public $abstract;

    public $note;

    public $customFields = [];

    public $customValues = [];

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->bibliometric = $project->bibliometric;

        for ($i = intval($this->bibliometric->start_year); $i <= $this->bibliometric->end_year; $i++) {
            array_push($this->years, $i);
        }

        $this->states = State::select('id', 'abbreviation')->orderBy('abbreviation')->get()->toArray();
        $this->customFields = $this->bibliometric->customFields;
    }

    public function save()
    {
        $data = $this->validate();
        $keywords = $this->serializeKeywords();

        \DB::beginTransaction();
        $createdProduction = $this->project->productions()->create($data);
        // $createdKeywords = $createdProduction->keywords()->create(['data' => $keywords]);
        $createdAuthors = $createdProduction->authors()->createMany($this->authors);
        if ($abstractData = $this->serializeAbstract()) {
            $createdProduction->abstract()->create($abstractData);
        }

        if ($createdProduction && $createdAuthors) {
            \DB::commit();

            foreach ($keywords as $keyword) {
                $createdProduction->keywords()->create(['value' => $keyword]);
            }

            if ($this->note) {
                $createdProduction->notes()->create([
                    'project_id' => $this->project->id,
                    'content' => $this->note,
                ]);
            }

            $this->fillCustomFields($createdProduction);

            session()->flash('status', 'Produção adicionada com sucesso.');
            $this->redirectRoute('project.bibliometrics.productions.show', [$this->project, $createdProduction], navigate: true);
        } else {
            \DB::rollBack();
            dump('Erro ao adicionar produção');
        }
    }

    public function serializeKeywords()
    {
        $keywords = str_replace(['.'], ';', $this->keywords);
        $keywords = array_filter(explode(';', $keywords));
        $keywords = array_map('trim', $keywords);
        $keywords = array_map(function ($keyword) {
            return \Str::ucfirst($keyword);
        }, $keywords);

        return $keywords;
    }

    public function serializeAbstract()
    {
        $abstract = $this->abstract ?? null;
        if (! $abstract) {
            return null;
        }

        $data = [];
        $data['section'] = 'resumo';
        $data['content'] = $this->abstract ?? null;
        $diacritics = 'aàȁáâǎãāăȃȧäåẚảạḁąᶏậặầằắấǻẫẵǡǟẩẳⱥæǽǣᴂꬱꜳꜵꜷꜹꜻꜽɐɑꭤᶐꬰɒͣᵃªᵄᵆᵅᶛᴬᴭᴀᴁₐbḃƅƀᵬɓƃḅḇᶀꞗȸßẞꞵꞛꞝᵇᵝᴮᴯʙᴃᵦcćĉčċƈçḉɕꞔꞓȼ¢ʗᴐᴒɔꜿᶗꝢꝣ©ͨᶜᶝᵓᴄdďḋᵭðđɗᶑḓḍḏḑᶁɖȡꝱǳʣǆʤʥȸǲǅꝺẟƍƌͩᵈᶞᵟᴰᴅᴆeèȅéēêěȇĕẽėëẻḙḛẹȩęᶒⱸệḝềḕếḗễểɇəǝɘɚᶕꬲꬳꬴᴔꭁꭂ•ꜫɛᶓȝꜣꝫɜᴈᶔɝɞƩͤᵉᵊᵋᵌᶟᴱᴲᴇⱻₑₔfẜẝƒꬵḟẛᶂᵮꞙꝭꝼʩꟻﬀﬁﬂﬃﬄᶠꜰgǵḡĝǧğġģǥꬶᵷɡᶃɠꞡᵍᶢᴳɢʛhħĥȟḣḧɦɧḫḥẖḩⱨꜧꞕƕɥʮʯͪʰʱꭜᶣᵸꟸᴴʜₕiìȉíīĩîǐȋĭïỉɨḭịįᶖḯıɩɪꭠꭡᴉᵻᵼĳỻİꟾꟷͥⁱᶤᶦᵎᶧᶥᴵᵢjȷĵǰɉɟʝĳʲᶡᶨᴶᴊⱼkḱǩꝁꝃꝅƙḳḵⱪķᶄꞣʞĸᵏᴷᴋₖlĺľŀłꝉƚⱡɫꬷꬸɬꬹḽḷḻļɭȴᶅꝲḹꞎꝇꞁỻǈǉʪʫɮˡᶩᶪꭝꭞᶫᴸʟᴌₗmḿṁᵯṃɱᶆꝳꬺꭑᴟɯɰꟺꟿꟽͫᵐᶬᶭᴹᴍₘnǹńñňŉṅᵰṇṉṋņŋɳɲƞꬻꬼȵᶇꝴꞃꞑꞥᴝᴞǋǌⁿᵑᶯᶮᶰᴺᴻɴᴎₙoᴏᴑòȍóǿőōõôȏǒŏȯöỏơꝍọǫⱺꝋɵøᴓǭộợồṑờốṍṓớỗỡṏȭȱȫổởœɶƣɸƍꝏʘꬽꬾꬿꭀꭁꭂꭃꭄꭢꭣ∅ͦᵒᶱºꟹᶲᴼᴽₒpṕṗꝕꝓᵽᵱᶈꝑþꝥꝧƥƪƿȹꟼᵖᴾᴘᴩᵨₚqʠɋꝙꝗȹꞯʘθᶿrŕȑřȓṙɍᵲꝵꞧṛŗṟᶉꞅɼɽṝɾᵳᴦɿſⱹɹɺɻ®ꝶꭇꭈꭉꭊꭋꭌͬʳʶʴʵᴿʀʁᴙᴚꭆᵣsśŝšṡᵴꞩṣşșȿʂᶊṩṥṧƨʃʄʆᶋᶘꭍʅƪﬅﬆˢᶳᶴꜱₛtťṫẗƭⱦᵵŧꝷṱṯṭţƫʈțȶʇꞇꜩʦʧʨᵺͭᵗᶵᵀᴛₜuùȕúűūũûǔȗŭüůủưꭒʉꞹṷṵụṳųᶙɥựǜừṹǘứǚữṻǖửʊᵫᵿꭎꭏꭐꭑͧᵘᶶᶷᵙᶸꭟᵁᴜᵾᵤvṽⱱⱴꝟṿᶌʋʌͮᵛⱽᶹᶺᴠᵥwẁẃŵẇẅẘⱳẉꝡɯɰꟽꟿʍʬꞶꞷʷᵚᶭᵂᴡxẋẍᶍ×ꭓꭔꭕꭖꭗꭘꭙˣ˟ᵡₓᵪyỳýȳỹŷẏÿẙỷƴɏꭚỵỿɣɤꝩʎƛ¥ʸˠᵞʏᵧzźẑžżƶᵶẓẕʐᶎʑȥⱬɀʒǯʓƺᶚƹꝣᵹᶻᶼᶽᶾᴢᴣ';
        $data['total_words'] = str_word_count($abstract, 0, $diacritics);

        return $data;
    }

    public function render()
    {
        return view('livewire.production.production-create')
            ->title('Adicionar produção');
    }

    public function addAuthor()
    {
        $this->validate([
            'author.forename' => 'required|string',
            'author.lastname' => 'required|string',
        ]);
        array_push($this->authors, ['forename' => $this->author['forename'], 'lastname' => $this->author['lastname']]);
        array_push($this->authors_display, ' '.$this->author['forename'].' '.$this->author['lastname']);
        $this->reset('author');
    }

    public function removeAuthor($key)
    {
        array_splice($this->authors, $key, 1);
        array_splice($this->authors_display, $key, 1);
    }

    public function titleToLower()
    {
        $title = \Str::lower($this->title);
        $this->title = \Str::ucfirst($title);
    }

    public function institutionToLower()
    {
        $institution = \Str::lower($this->institution);
        $this->institution = \Str::title($institution);
    }

    public function programToLower()
    {
        $program = \Str::lower($this->program);
        $this->program = \Str::title($program);
    }

    public function fillCustomFields($production)
    {
        $customValues = array_filter($this->customValues);

        $pivotData = [];
        foreach ($customValues as $customFieldId => $value) {
            $pivotData[$customFieldId] = ['value' => $value];
        }

        if ($production) {
            $production->customFields()->sync($pivotData);
        }

        // $this->customValues = [];
        // foreach ($this->customFields as $field) {
        //     $this->customValues[$field['name']] = null;
        // }
    }

    public function autoFill(AutoFillService $autoFillService)
    {
        $html = Http::get($this->url)->body();

        $result = $autoFillService->fill($html);

        if (isset($result['error'])) {
            session()->flash('error', 'Erro ao preencher os dados automaticamente: '.$result['error']);

            return;
        }

        $content = $result['choices'][0]['message']['content'];

        // Remove blocos de markdown (```json e ```)
        $cleanJson = trim($content);
        $cleanJson = preg_replace('/^```json|```$/', '', $cleanJson);

        // Decodifica para array associativo
        $data = json_decode($cleanJson, true);

        // Agora você pode acessar diretamente:
        $this->title = $data['title'] ?? null;
        $this->year = $data['year'] ?? null;
        $this->authors = $data['authors'] ?? [];
        $this->type = $data['type'] ?? null;
        $this->institution = $data['institution'] ?? null;
        $this->program = $data['program'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->keywords = $data['keywords'] ?? null;
        $this->abstract = $data['abstract'] ?? null;
        $this->periodical = $data['periodical'] ?? null;
        $this->doi = $data['doi'] ?? null;
        $this->language = $data['language'] ?? null;
        foreach ($this->authors as $author) {
            array_push($this->authors_display, ' '.$author['forename'].' '.$author['lastname']);
        }
    }
}
