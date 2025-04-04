export class Utils {
    static mod(a, b) {
        return ((a % b) + b) % b;
    }

    static formatUTCDateToLocal(utcDateString) {
        const utcDate = new Date(utcDateString);
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        const localDateString = utcDate.toLocaleString(undefined, options)
            .replace(/\//g, '.')
            .replace(',', '');
        return localDateString;
    }

    static interpolateRGB(color1, color2, fraction) {
        if (fraction < 0 || fraction > 1) {
            throw new Error("Fraction must be between 0 and 1");
        }
        function parseRGB(color) {
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
}
