<?php

namespace App\Http\Controllers\Web\Backend\V1;

use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Interfaces\LanguageServiceInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function __construct(private readonly LanguageServiceInterface $languageService)
    {
    }

    /**
     * Display a listing of languages.
     *
     * @param Request $request
     * @return View|JsonResponse
     *
     * @throws Exception
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = $this->languageService->getAllLatest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name ?? 'N/A';
                })
                ->addColumn('code', function ($data) {
                    return $data->code ?? 'N/A';
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor  = $data->status == 'active' ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == 'active' ? '26px' : '2px';

                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a href="' . route('language.edit', ['id' => $data->id]) . '" type="button" class="btn btn-primary fs-14 text-white edit-icn" title="Edit">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <a href="' . route('language.show', ['id' => $data->id]) . '" type="button" class="btn btn-info fs-14 text-white edit-icn" title="Show">
                                    <i class="mdi mdi-eye"></i>
                                </a>

                            </div>';
                })
                ->rawColumns(['name', 'code', 'status', 'action'])
                ->make();
        }

        return view('backend.v1.languages.index');
    }
// Delete action is removed as per the requirement. So, the delete button is also removed from the action column in the datatable.
    /*  <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="mdi mdi-delete"></i>
                                </a> */

    /**
     * Show the form for creating a new language.
     *
     * @return View
     */
    public function create(): View
    {
        return view('backend.v1.languages.create');
    }

    /**
     * Store a newly created language.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50|unique:languages,name',
                'code' => 'required|string|max:10|unique:languages,code',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->languageService->create($validator->validated());

            return redirect()->route('language.index')->with('t-success', 'Created Successfully !!');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong! ' . $e->getMessage());
        }
    }

    /**
     * Show the specified language.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $data = $this->languageService->findOrFail($id);

        return view('backend.v1.languages.show', compact('data'));
    }

    /**
     * Show the form for editing the specified language.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $data = $this->languageService->findOrFail($id);

        return view('backend.v1.languages.edit', compact('data'));
    }

    /**
     * Update the specified language.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50|unique:languages,name,' . $id,
                'code' => 'required|string|max:10|unique:languages,code,' . $id,
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $this->languageService->update($id, $validator->validated());

            return redirect()->route('language.index')->with('t-success', 'Updated Successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong! ' . $e->getMessage());
        }
    }

    /**
     * Toggle status of the language.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse
    {
        $data = $this->languageService->toggleStatus($id);

        if ($data->status === 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Published Successfully.',
            'data'    => $data,
        ]);
    }

    /**
     * Remove the specified language.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->languageService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the Data.',
            ]);
        }
    }
}








