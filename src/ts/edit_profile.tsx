import React, { useRef, useState } from 'react';
import ReactDOM from 'react-dom/client';
import { Utils } from './utils';
import { validateEmail } from './validation';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const initialData = JSON.parse(document.getElementById('initial-data')!.textContent!);

function TabButton(props: { children: React.ReactNode }) {
    return <div className="tabButton active">{props.children}</div>
}

function LabelField(props: { labelText: string, displayText: string  }) {
    return (
        <tr>
            <td><span className="profileLabelText">{props.labelText}:</span></td>
            <td><span className="profileDisplayText">{props.displayText}</span></td>
        </tr>
    );
}

function EditButton(props: { onClick: () => void }) {
    return (
        <button className="iconButton profileEditButton" onClick={props.onClick}>
            <img src="/icons/pencil.png" width="20" height="20"/>
            <span>Изменить</span>
        </button>
    );
}

function SaveButton(props: { onClick: () => void }) {
    return (
        <button className="iconButton profileSaveButton" onClick={props.onClick}>
            <img src="/icons/send.png" width="20" height="20"/>
            <span>Сохранить</span>
        </button>
    );
}

function CancelButton(props: { onClick: () => void }) {
    return (
        <button className="iconButton profileCancelButton" onClick={props.onClick}>
            <img src="/icons/cross.png" width="20" height="20"/>
            <span>Отмена</span>
        </button>
    );
}

function Spinner(props: { radius: number, factor: number }) {
    return (
        <svg className="spinner" viewBox="0 0 100 100">
            <circle 
                cx="50" 
                cy="50" 
                r={props.radius}
                fill="none" 
                stroke="red"
                strokeWidth="5" 
                strokeDasharray={`${2 * Math.PI * props.radius * props.factor} ${2 * Math.PI * props.radius * (1 - props.factor)}`}
                strokeLinecap="round"
            ></circle>
        </svg>
    );
}

function EditButtons(props: {
    fieldState: FieldState,
    editButtonClick: () => void,
    cancelButtonClick: () => void,
    saveButtonClick: () => void
}) {
    if (props.fieldState === FieldState.Normal) {
        return (
            <EditButton onClick={() => props.editButtonClick()} />
        );
    } else if (props.fieldState === FieldState.Editing) {
        return (
            <>
                <SaveButton onClick={() => props.saveButtonClick()} />
                <CancelButton onClick={() => props.cancelButtonClick()} />
            </>
        );
    } else if (props.fieldState === FieldState.Saving) {
        return (
            <Spinner radius={30} factor={2/3} />
        );
    }
}

enum FieldState {
    Normal,
    Editing,
    Saving
}

function TextField(props: {
    fetchUrl: string,
    errorPrefix: string,
    labelText: string,
    initialValue: string,
    inputType?: string,
    prepareRequest: (...inputValues: string[]) => { body: string, headers: Record<string, string> },
}) {
    const inputType = props.inputType || "text";
    const [fieldState, setFieldState] = useState(FieldState.Normal);
    const [displayedValue, setDisplayedValue] = useState(props.initialValue);
    const [inputValue, setInputValue] = useState(props.initialValue);
    const [error, setError] = useState("");

    function enableEditing() {
        setFieldState(() => FieldState.Editing);
    }

    function enableSaving() {
        setError(() => "");
        setFieldState(() => FieldState.Saving);
    }

    function disableEditing(confirm: boolean) {
        if (confirm) {
            setDisplayedValue(inputValue);
        } else {
            setInputValue(displayedValue);
        }
        setError(() => "");
        setFieldState(() => FieldState.Normal);
    }

    async function saveButtonClick() {
        enableSaving();
        const request = props.prepareRequest(inputValue);

        function handleError(errorMessage: string) {
            setError(errorMessage);
            enableEditing();
        }

        if (csrfToken) request.headers["X-CSRF-Token"] = csrfToken;
        try {
            const response = await fetch(props.fetchUrl, { method: "POST", body: request.body, headers: request.headers });
            await Utils.handleResponse(response, props.errorPrefix, () => disableEditing(true), handleError);
        } catch (error: any) {
            handleError(error.message);
        }
    }

    return (
        <tr>
            <td><span className="profileLabelText">{props.labelText}:</span></td>
            <td>
                <div className="profileErrorContainer">
                    {fieldState === FieldState.Normal ? <span className="profileDisplayText">{displayedValue}</span> : null}
                    {fieldState !== FieldState.Normal ? <input className="profileTextInput" type={inputType} value={inputValue} onChange={(e) => setInputValue(() => e.target.value)} /> : null}
                    {error ? <span className="profileErrorText">{error}</span> : null}
                </div>
            </td>
            <td>
                <div className="profileEditButtonsContainer">
                    <EditButtons
                        fieldState={fieldState}
                        editButtonClick={enableEditing}
                        cancelButtonClick={() => disableEditing(false)}
                        saveButtonClick={saveButtonClick}
                    />
                </div>
            </td>
        </tr>
    );
}

function Main() {
    return (
        <main>
            <div id="profileContainer">
                <h1 id="profileTitle">Профиль</h1>
                <div id="profileContent">
                    <div id="profileTabList" className="tabButtonList">
                        <TabButton>Главное</TabButton>
                    </div>
                    <div id="profileMainTab" className="profileTabBody card">
                        <table id="profileTable">
                            <tbody>
                                <LabelField labelText="Логин" displayText={initialData.username} />
                                <TextField
                                    fetchUrl="/change_email"
                                    errorPrefix="Изменение email"
                                    labelText="Email"
                                    initialValue={initialData.email}
                                    inputType="email"
                                    prepareRequest={(email) => ({
                                        body: email,
                                        headers: { "Content-Type": "text/plain" }
                                    })}
                                />
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    );
}

const container = document.getElementById('root') as HTMLElement;
const root = ReactDOM.createRoot(container);
root.render(<Main />);
