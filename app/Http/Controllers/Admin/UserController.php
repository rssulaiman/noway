<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Course;
use App\Models\Formation;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request)
    {
        $users = $this->userRepo->all();
        $type = $request->get('type');
        if (!in_array($type, ['etudiant', 'enseignant'])) {
            $type = null;
        }

        $query_search = $request->get('query');

        if ($query_search) {
            $users = $this->userRepo->findUser($query_search, $type)->get();
        } elseif ($type) {
            $users = $type == 'enseignant' ? $this->userRepo->getEnseignants() : $this->userRepo->getEtudiants();
        }

        return view('admin.users.index', [
            'users' => $users,
            'query' => $query_search,
            'type' => $type
        ]);
    }

    public function unvalidate(Request $request)
    {
        $this->userRepo->wantInvalidate();

        $users = $this->userRepo->all();

        $type = $request->get('type');
        if (!in_array($type, ['etudiant', 'enseignant'])) {
            $type = null;
        }

        $query_search = $request->get('query');

        if ($query_search) {
            $users = $this->userRepo->findUser($query_search, $type)->get();
        } elseif ($type) {
            $users = $type == 'enseignant' ? $this->userRepo->getEnseignants() : $this->userRepo->getEtudiants();
        }

        return view('admin.users.unvalidate', [
            'users' => $users,
            'query' => $query_search,
            'type' => $type
        ]);
    }

    public function create()
    {
        return view('admin.users.showForm', [
            'user' => new User,
            'courses' => $this->getAvailableCourses(),
            'formations' => Formation::all(),
            'editing' => false
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();

        if ($request->password) {
            $data['mdp'] = Hash::make($request->password);
        }

        if ($request->type != 'etudiant') {
            $data['formation_id'] = null;
        }

        if (User::create($data)) {
            return redirect()->route('admin.users.index')->with(['success' => 'L\'utilisateur a bien été ajouté !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur inattendue s\'est produite']);
    }

    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        return view('admin.users.showForm', [
            'user' => $user,
            'editing' => true,
            'courses' => $this->getAvailableCourses($user->id),
            'formations' => Formation::all(),
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        if ($request->password) {
            $data['mdp'] = Hash::make($request->password);
        }

        if ($request->type != 'etudiant') {
            $data['formation_id'] = null;
        }

        if ($user->update($data)) {
            $courses = $request->get('courses') ?? [];
            if ($user->is_etudiant) {
                $user->courses()->sync($courses);
            } else if ($user->is_enseignant) {
                foreach ($user->courses as $course) {
                    if (!in_array($course->id, $courses)) {
                        $course->enseignant()->dissociate();
                        $course->save();
                    }
                }

                foreach ($request->getNewCourses() as $course) {
                    $course->enseignant()->associate($user->id);
                    $course->save();
                }
            }

            return redirect()->route('admin.users.index')->with(['success' => 'L\'utilisateur a bien été modifié !']);
        }

        return redirect('/', 500)->with(['error' => 'Une erreur inattendue s\'est produite']);
    }

    public function forceDelete(User $user)
    {
        if ($user->forceDelete()) {
            return redirect()->route('admin.users.index');
        }

        return redirect('/', 500)->with(['error' => 'Une erreur inattendue s\'est produite']);
    }

    public function destroy(User $user)
    {
        if ($user->delete()) {
            return redirect()->route('admin.users.index');
        }

        return redirect('/', 500)->with(['error' => 'Une erreur inattendue s\'est produite']);
    }

    public function getAvailableCourses($user_id = null)
    {
        $courses = Course::where('user_id', null);
        if ($user_id) {
            $courses = $courses->orWhere('user_id', $user_id);
        }

        return $courses->get();
    }
}
