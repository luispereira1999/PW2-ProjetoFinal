<!-- DEFINIÇÃO: modal de remover um post -->

<div class="modal fade" id="deleteUser{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remover Utilizador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="modal__paragraph">Deseja mesmo remover este post?</p>

                <form id="formDeleteUser{{ $user->id }}" method="post" action="{{ route('account.delete', ['userId' => $user->id]) }}">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            <div class="modal-footer">
                <button class="button button-danger" type="submit" form="formDeleteUser{{ $user->id }}">Remover</button>
                <button class="button button-cancel" type="button" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>
