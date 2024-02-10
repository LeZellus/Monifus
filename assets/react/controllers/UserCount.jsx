import React, { useState, useEffect } from 'react';

function DataFetcher() {

    const [data, setData] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            setIsLoading(true);
            setError(null);
            try {
                const response = await fetch('/api/user/count');
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                const json = await response.json();
                setData(json);
            } catch (error) {
                setError(error.message);
            } finally {
                setIsLoading(false);
            }
        };

        fetchData();
    }, []);

    if (isLoading) return <div>Chargement...</div>;
    if (error) return <div>Erreur: {error}</div>;
    if (!data) return <div>Pas de données disponibles</div>;

    return (
        <div className={"flex items-center uppercase text-xs"}>
            Nombre d'utilisateurs :
            <span className={"text-green-400 font-bold ml-2"}>{JSON.stringify(data.count)}</span>
        </div>
    );
}

export default DataFetcher;
