import React, { useRef, useState } from 'react';
import ReactDOM from 'react-dom/client';

function getUsername(): string {
    return "User";
}

function getEmail(): string {
    return "user@example.com";
}

function TabButton(props: { children: React.ReactNode }) {
    return <div className="tabButton active">{props.children}</div>
}

function AbstractField(props: { children: React.ReactNode }) {
    const fetchUrl = useRef(null);
    const errorPrefix = useRef(null);
    return <>{props.children}</>;
}

function LabelField(props: { labelText: string, displayText: string  }) {
    return (
        <AbstractField>
            <tr>
                <td><span className="profileLabelText">{props.labelText}:</span></td>
                <td><span className="profileDisplayText">{props.displayText}</span></td>
            </tr>
        </AbstractField>
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

function TextField(props: { labelText: string, displayText: string, inputType?: string }) {
    const inputType = props.inputType || "text";
    const [editMode, setEditMode] = useState(false);
    const [error, setError] = useState("");

    function enableEditing() {
        setEditMode(true);
    }

    function disableEditing(confirm: boolean) {
        setEditMode(false);
    }

    return (
        <AbstractField>
            <tr>
                <td><span className="profileLabelText">{props.labelText}:</span></td>
                <td>
                    <div className="profileErrorContainer">
                        {!editMode ? <span className="profileDisplayText">{props.displayText}</span> : null}
                        {editMode ? <input className="profileTextInput" type={inputType}/> : null}
                        {error ? <span className="profileErrorText hidden"></span> : null}
                    </div>
                </td>
                <td>
                    <div className="profileEditButtonsContainer">
                        {!editMode
                        ?
                        <EditButton onClick={() => enableEditing()} />
                        :
                        <>
                            <SaveButton onClick={() => disableEditing(true)} />
                            <CancelButton onClick={() => disableEditing(false)} />
                        </>}
                    </div>
                </td>
            </tr>
        </AbstractField>
    );
}

function ImageUploadField() {
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
                                <LabelField labelText="Логин" displayText={getUsername()} />
                                <TextField labelText="Email" displayText={getEmail()} />
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
