const Card = ({
    children,
    title,
    subtitle,
    footer,
    padding = true,
    className = '',
    ...props
}) => {
    return (
        <div
            className={`bg-white rounded-lg shadow-md border border-gray-200 ${className}`}
            {...props}
        >
            {(title || subtitle) && (
                <div className={`border-b border-gray-200 ${padding ? 'px-6 py-4' : ''}`}>
                    {title && (
                        <h3 className="text-lg font-semibold text-gray-900">{title}</h3>
                    )}
                    {subtitle && (
                        <p className="text-sm text-gray-600 mt-1">{subtitle}</p>
                    )}
                </div>
            )}
            <div className={padding ? 'p-6' : ''}>
                {children}
            </div>
            {footer && (
                <div className={`border-t border-gray-200 bg-gray-50 rounded-b-lg ${padding ? 'px-6 py-4' : ''}`}>
                    {footer}
                </div>
            )}
        </div>
    );
};

export default Card;
