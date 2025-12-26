// Accordéon : Gestion de l'affichage
function toggleAccordion(header) {
    header.classList.toggle('active');
    const content = document.getElementById('balanceAccordionContent');
    if (content.style.maxHeight) {
        content.style.maxHeight = null;
        content.classList.remove('open');
    } else {
        content.classList.add('open');
        content.style.maxHeight = content.scrollHeight + "px";
    }
}

// MODALES DÉPENSES

function openExpenseDeleteModal(url) {
    const btn = document.getElementById('confirmExpenseDeleteBtn');
    if (btn) btn.href = url;
    document.getElementById('deleteExpenseModal').classList.add('active');
}

function closeExpenseDeleteModal() {
    document.getElementById('deleteExpenseModal').classList.remove('active');
}

function openExpenseDetail(desc, amount, iconClass, name, email, avatar, date) {
    document.getElementById('detailDesc').textContent = desc;
    document.getElementById('detailAmount').textContent = amount + ' €';
    document.getElementById('detailIcon').innerHTML = `<i class="${iconClass}"></i>`;
    document.getElementById('detailName').textContent = name;
    document.getElementById('detailEmail').textContent = email;
    document.getElementById('detailAvatar').src = avatar;
    document.getElementById('detailDate').textContent = date;

    document.getElementById('detailExpenseModal').classList.add('active');
}

function closeExpenseDetail() {
    document.getElementById('detailExpenseModal').classList.remove('active');
}

// MODALES MEMBRES

function openMembersModal() {
    const modal = document.getElementById('membersModalOverlay');
    if (modal) modal.classList.add('active');
}

function closeMembersModal() {
    const modal = document.getElementById('membersModalOverlay');
    if (modal) modal.classList.remove('active');
}

// Gestion Suppression Membre (Admin)
function openMemberDeleteModal(id, name, urlTemplate) {
    closeMembersModal();
    document.getElementById('spanMemberName').textContent = name;

    // Remplacement du placeholder par l'ID réel
    let finalUrl = urlTemplate.replace('PLACEHOLDER', id);

    document.getElementById('deleteMemberForm').action = finalUrl;
    document.getElementById('deleteMemberModal').classList.add('active');
}

function closeMemberDeleteModal() {
    document.getElementById('deleteMemberModal').classList.remove('active');
}

// Fermeture au clic sur l'overlay
window.onclick = function (event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
}
