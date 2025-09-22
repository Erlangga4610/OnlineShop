<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.shop')]
class Profile extends Component
{
    public $name, $email, $password;
    public $first_name, $last_name, $gender, $biodata;
    public $recipient_name, $phone, $address, $city, $province, $postal_code, $country;

    public function mount()
    {
        $user = Auth::user();

        // Data user
        $this->fill([
            'name'       => $user->name,
            'email'      => $user->email,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'gender'     => $user->gender,
            'biodata'    => $user->biodata,
        ]);

        // Data alamat (jika ada)
        if ($address = $user->addresses()->first()) {
            $this->fill([
                'recipient_name' => $address->recipient_name,
                'phone'          => $address->phone,
                'address'        => $address->street,
                'city'           => $address->city,
                'province'       => $address->province,
                'postal_code'    => $address->postal_code,
                'country'        => $address->country,
            ]);
        }
    }

    public function updateProfile()
    {
        try {
            $this->resetErrorBag();

            $this->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email|unique:users,email,' . Auth::id(),
                'password'   => 'nullable|min:6',
                'first_name' => 'nullable|string|max:255',
                'last_name'  => 'nullable|string|max:255',
                'gender'     => 'nullable|in:male,female,other',
                'biodata'    => 'nullable|string',
            ]);

            $user = Auth::user();
            $user->fill([
                'name'       => $this->name,
                'email'      => $this->email,
                'first_name' => $this->first_name,
                'last_name'  => $this->last_name,
                'gender'     => $this->gender,
                'biodata'    => $this->biodata,
            ]);

            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }

            $user->save();

            $this->dispatch('toastr', type: 'success', message: 'Profil berhasil diperbarui ✅');
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Gagal menyimpan profil ❌');
        }
    }

    public function updateAddress()
    {
        try {
            $this->resetErrorBag();

            $this->validate([
                'recipient_name' => 'required|string|max:255',
                'phone'          => 'required|string|max:20|regex:/^[0-9\+\-\(\)\s]+$/',
                'address'        => 'required|string|max:255',
                'city'           => 'required|string|max:100',
                'province'       => 'required|string|max:100',
                'postal_code'    => 'required|string|max:20',
                'country'        => 'required|string|max:100',
            ]);

            $user = Auth::user();

            // 1 user = 1 alamat
            $address = $user->addresses()->first() ?? new Address(['user_id' => $user->id]);

            $address->fill([
                'recipient_name' => $this->recipient_name,
                'phone'          => $this->phone,
                'street'         => $this->address,
                'city'           => $this->city,
                'province'       => $this->province,
                'postal_code'    => $this->postal_code,
                'country'        => $this->country,
            ])->save();

            $this->dispatch('toastr', type: 'success', message: 'Alamat berhasil diperbarui ✅');
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Gagal menyimpan alamat ❌');
        }
    }

    public function saveAll()
    {
        try {
            $this->updateProfile();
            $this->updateAddress();
        } catch (\Exception $e) {
            // Tangani kesalahan jika diperlukan
        }
    }

    public function render()
{
    return view('livewire.auth.profile');
}

}
