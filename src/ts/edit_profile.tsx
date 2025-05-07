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
                strokeDasharray={
                    `${2 * Math.PI * props.radius * props.factor}`
                    + `${2 * Math.PI * props.radius * (1 - props.factor)}`
                }
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

type ReactInputElement = React.ReactElement<React.InputHTMLAttributes<HTMLInputElement>>;

function TextField(props: {
    fetchUrl: string,
    errorPrefix: string,
    labelText: string,
    pass?: (displayValue: string, ...inputValues: string[]) => any,
    validate?: (...inputValues: string[]) => string | null,
    prepareRequest: (...inputValues: string[]) => { body: string, headers: Record<string, string> },
    onEnable?: (params: {
        displayValue: string,
        inputValues: string[],
        setDisplayedValue: React.Dispatch<React.SetStateAction<string>>,
        setInputValues: React.Dispatch<React.SetStateAction<string[]>>,
    }) => void,
    onDisable?: (params: {
        confirm: boolean,
        displayValue: string,
        inputValues: string[],
        setDisplayedValue: React.Dispatch<React.SetStateAction<string>>,
        setInputValues: React.Dispatch<React.SetStateAction<string[]>>,
    }) => void,
    children: ReactInputElement | ReactInputElement[],
}) {
    const childrenArray = React.Children.toArray(props.children) as ReactInputElement[];
    const initialValues = childrenArray.map((child) => child.props.value) as string[];

    const [fieldState, setFieldState] = useState(FieldState.Normal);
    const [displayedValue, setDisplayedValue] = useState(initialValues[0]);
    const [inputValues, setInputValues] = useState<string[]>(initialValues);
    const [error, setError] = useState("");

    function enableEditing() {
        if (props.onEnable) {
            props.onEnable({
                displayValue: displayedValue,
                inputValues,
                setDisplayedValue,
                setInputValues,
            });
        }
        setFieldState(() => FieldState.Editing);
    }

    function enableSaving() {
        setError(() => "");
        setFieldState(() => FieldState.Saving);
    }

    function disableEditing(confirm: boolean) {
        if (props.onDisable) {
            props.onDisable({
                confirm,
                displayValue: displayedValue,
                inputValues: inputValues,
                setDisplayedValue,
                setInputValues,
            });
        }
        setError(() => "");
        setFieldState(() => FieldState.Normal);
    }

    async function saveButtonClick() {
        if (props.pass) {
            if (props.pass(displayedValue, ...inputValues)) {
                disableEditing(true);
                return;
            }
        }
        if (props.validate) {
            const errorMessage = props.validate(...inputValues);
            if (errorMessage) {
                setError(errorMessage);
                return;
            }
        }

        enableSaving();
        const request = props.prepareRequest(...inputValues);

        function handleError(errorMessage: string) {
            setError(errorMessage);
            enableEditing();
        }

        if (csrfToken) request.headers["X-CSRF-Token"] = csrfToken;
        try {
            const response = await fetch(props.fetchUrl, {
                method: "POST",
                body: request.body,
                headers: request.headers
            });
            await Utils.handleResponse({
                response,
                errorPrefix: props.errorPrefix,
                onSuccess: () => disableEditing(true),
                onError: handleError
            });
        } catch (error: any) {
            handleError(error.message);
        }
    }

    const inputsWithListeners = React.Children.map(props.children, (element: ReactInputElement, index: number) => {
        return React.cloneElement(element, {
            onChange: (e: React.ChangeEvent<HTMLInputElement>) => setInputValues(() => Utils.updateItem(inputValues, index, e.target.value)),
            value: inputValues[index]
        });
    });

    return (
        <tr>
            <td><span className="profileLabelText">{props.labelText}:</span></td>
            <td>
                <div className="profileErrorContainer">
                    {fieldState === FieldState.Normal ?
                        <span
                            className="profileDisplayText">{displayedValue}
                        </span>
                        : null}
                    {fieldState !== FieldState.Normal ? inputsWithListeners : null}
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
                                    pass={(emailOld, emailNew) => emailNew === emailOld}
                                    validate={(email) => {
                                        if (!email) return "Введите email";
                                        const error = validateEmail(email);
                                        return error;
                                    }}
                                    prepareRequest={(email) => ({
                                        body: email,
                                        headers: { "Content-Type": "text/plain" }
                                    })}
                                    onDisable={({confirm, displayValue, inputValues, setDisplayedValue, setInputValues}) => {
                                        if (confirm) {
                                            setDisplayedValue(() => inputValues[0]);
                                        } else {
                                            setInputValues(() => [displayValue]);
                                        }
                                    }}
                                >
                                    <input className="profileTextInput" 
                                        type="email"
                                        value={initialData.email}
                                    />
                                </TextField>
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
