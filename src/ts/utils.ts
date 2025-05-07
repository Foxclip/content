export class Utils {

    static mod(a: number, b: number) {
        return ((a % b) + b) % b;
    }

    static formatUTCDateToLocal(utcDateString: string) {
        if (!utcDateString.endsWith('Z')) {
            utcDateString += 'Z';
        }
        const utcDate = new Date(utcDateString);
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        } as Intl.DateTimeFormatOptions;
        const localDateString = utcDate.toLocaleString(undefined, options)
            .replace(/\//g, '.')
            .replace(',', '');
        return localDateString;
    }

    static interpolateRGB(color1: string, color2: string, fraction: number) {
        if (fraction < 0 || fraction > 1) {
            throw new Error("Fraction must be between 0 and 1");
        }
        function parseRGB(color: string) {
            const matches = color.match(/\d+/g);
            if (!matches || matches.length !== 3) {
                throw new Error("Invalid RGB format");
            }
            return matches.map(Number);
        }
        const [r1, g1, b1] = parseRGB(color1);
        const [r2, g2, b2] = parseRGB(color2);
        const interpolatedR = Math.round(r1 + (r2 - r1) * fraction);
        const interpolatedG = Math.round(g1 + (g2 - g1) * fraction);
        const interpolatedB = Math.round(b1 + (b2 - b1) * fraction);
        return `rgb(${interpolatedR}, ${interpolatedG}, ${interpolatedB})`;
    }

    static getCssVariable(name: string) {
        return getComputedStyle(document.documentElement).getPropertyValue(name);
    }

    static async handleResponse({
        response,
        errorPrefix,
        onSuccess,
        onError,
    }: {
        response: Response,
        errorPrefix: string,
        onSuccess?: (response: any) => void,
        onError?: (errorMessage: string) => void,
    }) {
        if (response.ok) {
            let responseJson: any;
            try {
                responseJson = await response.json();
            } catch (error: any) {
                if (onError) onError(error.message);
                console.log(`${errorPrefix}: ошибка json: ${error.message}`);
            }
            if (responseJson.success) {
                if (onSuccess) onSuccess(responseJson);
                console.log(`${errorPrefix}: успешно`);
            } else {
                if (onError) onError(responseJson.message);
                console.log(`${errorPrefix}: ошибка: ${responseJson.message}`);
            }
        } else {
            let message = `HTTP ${response.status} (${response.statusText})`;
            if (onError) onError(message);
            console.log(`${errorPrefix}: ошибка: ${message}`);
        }
    }

    static updateItem<T>(items: T[], itemIndex: number, newItem: T): T[] {
        const newItems = [...items];
        newItems[itemIndex] = newItem;
        return newItems;
    }

}
