interface JQuery {
    bootstrapTable(options: object): JQuery;

    bootstrapTable(method: string, ...parameters: any[]): JQuery | any;
}

declare function isNaN(value: string): boolean;