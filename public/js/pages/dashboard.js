(() => {

    const store = (data = {}) => window.localStorage.setItem('data', JSON.stringify(data));
    const storeData = () => JSON.parse(window.localStorage.getItem('data'));
    const expenseForm = document.querySelector('form[name=createExpense]');

    const getTemplateClone = selector => {
        const template = document.getElementById(selector);
        return template.content.cloneNode(true);
    }

    const displayBudget = (budget = {}) => {}

    const displayExpense = (expense = {}, container) => {
        const clone = getTemplateClone('expenseTemplate');
        clone.id = `expense__${expense.id}`;
        clone.querySelector('[data-expense-category-icon]').classList.add(expense?.category?.icon);
        clone.querySelector('[data-expense-category-name]').innerText = expense?.category?.name;
        clone.querySelector('[data-expense-amount]').innerText = expense?.amount.toFixed(2);
        clone.querySelector('[data-expense-label]').innerText = expense?.label;

        return container.appendChild(clone);
    }

    const filterBudgetExpenses = () => {}
    
    const displayData = () => {
        const expensesContainer = document.getElementById('expensesContainer');
        expensesContainer.innerHTML = '';
        storeData()?.unPaid.forEach((e) => {
            displayExpense(e, expensesContainer);
        })
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
                method: form.method,
                body: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json"
                },
            });
            if (response.ok && response.status === 201) {
                const expense = response.json();
                // Remise à zéro du formulaire
                form.reset();
                // Ajouter la dépense dans le store
                loadData();
                // Recharger la vue
                displayData();
                // Fermer la modale
                form.querySelector('[data-bs-dismiss="modal"]').click();
            }
        } catch (e) {
        }
    }

    const handleUpdateExpense = (e) => {}

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

    // Form handle submit 
    expenseForm.addEventListener('submit', handleCreateExpense);

    window.onload = () => {
        loadData();
        loadFormCategories();
    }

})();