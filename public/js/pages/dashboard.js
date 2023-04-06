(() => {

    const store = (data = {}) => window.localStorage.setItem('data', JSON.stringify(data));

    const loadData = async () => {
        try {
            const response = await fetch(API_DATA_URL);
            if (response.ok && response.status === 200) {
                const data = await response.json();
                store(data);
            }
        } catch (e) {
            console.error(e)
        }

    }

    const handleCreateExpense = (e) => {}

    const handleUpdateExpense = (e) => {}

    const displayBudget = (budget = {}) => {}

    const displayExpense = (expense = {}) => {}

    const filterBudgetExpenses = () => {}

    const getTemplateClode = selector => {
        const template = document.getElementById(selector);
        return template.content.cloneNode(true);
    }

    window.onload = () => {
        loadData();
    }

})();