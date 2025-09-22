<div>

    @include('components.shop.topbar')

    
    @include('components.shop.navbar')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card o-hidden border-0 shadow-lg my-4">
                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">My Profile & Address</h4>

                        <form wire:submit.prevent="saveAll">
                            {{-- ========== Bagian Profil ========== --}}
                            <div class="form-group mb-3">
                                <label>Username</label>
                                <input type="text" wire:model="name" class="form-control">
                                @error('name') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" wire:model="email" class="form-control">
                                @error('email') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Password Baru (opsional)</label>
                                <div class="input-group">
                                    <input type="password" wire:model="password" class="form-control" id="passwordInput">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa-solid fa-eye-slash" id="iconPassword"></i>
                                    </button>
                                </div>
                                @error('password') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label>First Name</label>
                                    <input type="text" wire:model="first_name" class="form-control">
                                    @error('first_name') <span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name</label>
                                    <input type="text" wire:model="last_name" class="form-control">
                                    @error('last_name') <span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Gender</label>
                                <select wire:model="gender" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                    <option value="other">Lainnya</option>
                                </select>
                                @error('gender') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group mb-4">
                                <label>Biodata</label>
                                <textarea wire:model="biodata" class="form-control" rows="3" placeholder="Masukkan biodata Anda"></textarea>
                                @error('biodata') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            {{-- ========== Bagian Alamat ========== --}}
                            <h5 class="mt-4">Alamat Pengiriman</h5>

                            <div class="form-group mb-3">
                                <label>Nama Penerima</label>
                                <input type="text" wire:model="recipient_name" class="form-control">
                                @error('recipient_name') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Nomor Telepon</label>
                                <input type="text" wire:model="phone" class="form-control">
                                @error('phone') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Alamat Lengkap</label>
                                <input type="text" wire:model="address" class="form-control">
                                @error('address') <span class="text-danger small">{{ $message }}</span>@enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label>Kota</label>
                                    <input type="text" wire:model="city" class="form-control">
                                    @error('city') <span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Provinsi</label>
                                    <input type="text" wire:model="province" class="form-control">
                                    @error('province') <span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label>Kode Pos</label>
                                    <input type="text" wire:model="postal_code" class="form-control">
                                    @error('postal_code') <span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Negara</label>
                                    <input type="text" wire:model="country" class="form-control">
                                    @error('country') <span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            {{-- Tombol submit gabungan --}}
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-save"></i> Simpan Semua
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.shop.footer')

    {{-- JS Toastr & Toggle Password --}}
    <script>
        $(document).ready(function () {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "3000"
            };

            // Toggle password
            $('#togglePassword').on('click', function () {
                const input = $('#passwordInput');
                const icon = $(this).find('i');
                input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
                icon.toggleClass('fa-eye fa-eye-slash');
            });
        });

        document.addEventListener("livewire:init", () => {
            Livewire.on("toastr", (data) => {
                toastr[data.type](data.message, data.type.charAt(0).toUpperCase() + data.type.slice(1));
            });
        });
    </script>
</div>
