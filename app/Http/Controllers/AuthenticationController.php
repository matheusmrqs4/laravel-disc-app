<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authService
    ) {
    }

    /**
     * @return View
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $result = $this->authService->registerUser($data);

        $request->session()->put('token', $result['token']);

        return redirect()
            ->route('login'); //TODO: alterar para view guilds.index
    }

    /**
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws \App\Exceptions\AuthenticationException
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        $result = $this->authService->loginUser($credentials);

        $request->session()->put('token', $result['token']);

        return redirect()
            ->route('register'); //TODO: alterar para view guilds.index
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logoutUser();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.post');
    }

    /**
     * @return JsonResponse
     * @throws \App\Exceptions\AuthenticationException
     */
    public function getUser(): JsonResponse
    {
        $user = $this->authService->getAuthenticatedUser();

        return response()
            ->json([
                'user' => $user,
            ], Response::HTTP_OK);
    }
}
