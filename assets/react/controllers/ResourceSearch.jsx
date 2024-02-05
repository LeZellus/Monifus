import React, { useState, useEffect } from 'react';
import axios from 'axios';

function ResourceSearch() {
    const [term, setTerm] = useState('');
    const [results, setResults] = useState([]);

    useEffect(() => {
        const search = async () => {
            if(term) {
                try {
                    const { data } = await axios.get(`/api/resources/search?term=${term}`);
                    console.log(data);
                    setResults(data.resources);
                } catch (error) {
                    console.error("Erreur lors de la requête: " , error);
                    setResults([]);
                }
            } else {
                setResults([]);
            }
        }

        const timeoutId = setTimeout(() => {
            search();
        }, 500);

        return () => {
            clearTimeout(timeoutId);
        }
    }, [term]);

    const renderedResults = results ? results.map(resource => (
        <div key={resource.id}>
            {resource.name}
        </div>
    )) : null;

    return (
        <div>
            <input
                type="text"
                onChange={(e) => setTerm(e.target.value)}
                placeholder="Rechercher une ressource..."
            />
            <div>
                {renderedResults}
            </div>
        </div>

    )
}

export default ResourceSearch;