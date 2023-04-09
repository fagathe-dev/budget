const store = (data = {}) => window.localStorage.setItem('data', JSON.stringify(data));
const storeData = () => JSON.parse(window.localStorage.getItem('data'));
const expenseForm = document.getElementById('expenseForm');

const getTemplateClone = selector => {
    const template = document.getElementById(selector);
    return template.content.cloneNode(true);
}

const sum = (arr = []) => arr.reduce((sum, c) => sum + c.amount, 0);

const initForm = () => {
    // Réinitialiser le formulaire
    expenseForm.reset();
    // Redéfinir l'action du formulaire
    expenseForm.setAttribute('data-action', 'create');
    // Fermer la modale
    expenseForm.querySelector('[data-bs-dismiss="modal"]').click();
    
    expenseForm.querySelector('[name="category"] option[selected]')?.removeAttribute('selected');
    
    return; 
}

const displayBudget = (budget = {}, expenses = [], container) => {
    const clone = getTemplateClone('budgetTemplate');
    clone.id = `budget__${budget.id}`;
    clone.querySelector('[data-budget-category-icon]').classList.add(budget?.category?.icon);
    clone.querySelector('[data-budget-category-name]').innerText = budget?.category?.name;
    clone.querySelector('[data-budget-expenses-sum]').innerText = sum(expenses).toFixed(2);
    clone.querySelector('[data-budget-amount]').innerText = budget?.amount;
    
    return container.appendChild(clone);
}

const displayExpense = (expense = {}, container) => {
    const clone = getTemplateClone('expenseTemplate');
    clone.id = `expense__${expense.id}`;
    clone.querySelector('[data-expense-category-icon]').classList.add(expense?.category?.icon);
    clone.querySelector('[data-expense-category-name]').innerText = expense?.category?.name;
    clone.querySelector('[data-expense-amount]').innerText = expense?.amount.toFixed(2);
    clone.querySelector('[data-expense-label]').innerText = expense?.label;
    clone.querySelector('[data-expense-edit]').setAttribute('onclick', `fillExpenseForm(${expense.id})`);
    clone.querySelector('[data-expense-delete]').href = API_DELETE_EXPENSE + expense.id;

    return container.appendChild(clone);
}

const filterBudgetExpenses = () => {
    return storeData()?.expenses?.reduce((acc, obj) => {
        const k = obj?.category?.slug;
        if(!acc[k]){
            acc[k] = [];
        }
        acc[k].push(obj);
        return acc;
    }, {});
}

const displayData = () => {
    const expensesContainer = document.getElementById('expensesContainer');
    const budgetsContainer = document.getElementById('budgetsContainer');
    expensesContainer.innerHTML = '';
    budgetsContainer.innerHTML = '';
    const filterBudgets = filterBudgetExpenses();
    storeData()?.unPaid.forEach((e) => {
        displayExpense(e, expensesContainer);
    });
    storeData()?.budgets.forEach((b) => {
        displayBudget(b, filterBudgets[b.category.slug], budgetsContainer);
    });
}

const loadData = async () => {
    try {
        const response = await fetch(API_DATA_URL);
        if (response.ok && response.status === 200) {
            const data = await response.json();
            store(data);
            displayData();
        }
    } catch (e) {
        console.error(e)
    }

}

const handleCreateExpense = async (e) => {
    e.preventDefault();
    const url = API_CREATE_EXPENSE;
    const form = e.target;
    const data = getValues(form);
    data.amount = Number(data.amount);
    data.isPaid = form.querySelector('[name="isPaid"]').checked;
    data.category = data.category !== null ? parseInt(data.category) : null;

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json"
            },
        });
        if (response.ok && response.status === 201) {
            // Remise à zéro du formulaire
            form.reset();
            // Recharger les data
            loadData();
            // Recharger la vue
            displayData();
            // Init le formulaire
            initForm();
        }
    } catch (e) {
    }
}

const fillExpenseForm = (id) => {
    const expense = storeData().expenses.find(obj => id === obj.id);
    const form = expenseForm;
    window.sessionStorage.setItem('current_edit', expense?.id);

    form.setAttribute('data-action', 'edit');
    form.querySelector('[name="label"]').value = expense?.label;
    form.querySelector('[name="amount"]').value = expense?.amount;
    form.querySelector('[name="isPaid"]').checked = expense?.isPaid;
    if (expense.category) {
        form.querySelector('[name="category"]').querySelector(`option[value="${expense.category?.id}"]`).setAttribute('selected', 'selected');
    }
}

const handleUpdateExpense = async (e) => {
    const url = API_EDIT_EXPENSE + window.sessionStorage.getItem('current_edit');
    const form = e.target;
    const data = getValues(form);
    data.amount = Number(data.amount);
    data.isPaid = form.querySelector('[name="isPaid"]').checked;
    data.category = data.category !== null ? parseInt(data.category) : null;

    try {
        const response = await fetch(url, {
            method: 'PUT',
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json"
            },
        });
        
        if (response.ok && response.status === 200) {
            // Recharger les data
            loadData();
            // Recharger la vue
            displayData();
            // Init le formulaire
            initForm();
            window.sessionStorage.removeItem('current_edit');
        }
    } catch (e) {
    }
}

const loadFormCategories = () => {
    if (storeData().hasOwnProperty('categories')) {
        storeData().categories.forEach(c => {
            const option = document.createElement('option');
            option.value = c.id;
            option.innerHTML = c.name;

            return expenseForm.querySelector('select[name="category"]').insertAdjacentElement('beforeend', option);
        })
    }
}

const handleDeleteExpense = async (e) => {
    e.preventDefault();
    const consent = confirm('Êtes-vous sûr de supprimer cette dépense ?');
    const url = e.target.href;

    if (consent) {
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    "Content-Type": "application/json"
                },
            });
            
            if (response.ok && response.status === 204) {
                // Recharger les data
                loadData();
            }
        } catch (e) {
        }
    }
}

// Form handle submit 
expenseForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    return await e.target.dataset?.action === 'create' ? handleCreateExpense(e) : handleUpdateExpense(e);
});

window.onload = () => {
    // Recharger les data
    loadData();
    loadFormCategories();
}